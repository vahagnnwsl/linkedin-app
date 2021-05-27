<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\ConnectionRequestRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\KeyRepository;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{

    protected $connectionRepository;

    protected $accountRepository;

    protected $keyRepository;

    protected $conversationRepository;

    protected $connectionRequestRepository;

    /**
     * ConnectionController constructor.
     * @param ConnectionRepository $connectionRepository
     * @param AccountRepository $accountRepository
     * @param KeyRepository $keyRepository
     * @param ConnectionRequestRepository $connectionRequestRepository
     * @param ConversationRepository $conversationRepository
     */

    public function __construct(ConnectionRepository $connectionRepository, AccountRepository $accountRepository, KeyRepository $keyRepository, ConnectionRequestRepository $connectionRequestRepository, ConversationRepository $conversationRepository)
    {
        $this->connectionRepository = $connectionRepository;
        $this->accountRepository = $accountRepository;
        $this->keyRepository = $keyRepository;
        $this->connectionRequestRepository = $connectionRequestRepository;
        $this->conversationRepository = $conversationRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $filterAttributes = ['account', 'name'];
        $data = $request->all();
        $enableKeysIdes = [];

        if (Auth::user()->hasRole('Hr')) {

            $keys = Auth::user()->keys;

            $enableKeysIdes = Auth::user()->keys->pluck('id')->toArray();

        } else {

            $keys = $this->keyRepository->getAll();
        }

        $relatedAccountsIdes = Auth::user()->unRealAccounts()->pluck('id')->toArray();

        $data['enableKeysIdes'] = $enableKeysIdes;


        $connections = $this->connectionRepository->filter($data, 'id');

        $userAccount = Auth::user()->account;

        return view('dashboard.connections.index', compact('connections', 'filterAttributes', 'keys', 'userAccount','relatedAccountsIdes'));
    }


    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function getInfo(int $id): RedirectResponse
    {

        $account = Auth::user()->account;

        $connection = $this->connectionRepository->getById($id);

        $data = Api::profile($account->login, $account->password)->getProfile($connection->publicIdentifier);

        $this->connectionRepository->update($id, ['data' => $data]);

        $this->putFlashMessage(true, 'Successfully updated');

        return redirect()->back();
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getTrackingId(int $id): JsonResponse
    {

        $account = Auth::user()->account;


        if ($account->getSendRequestCount() >= $account->limit_connection_request) {
            return response()->json([
                'limitError' => 'Daily limit is consume'
            ]);
        }

        $connection = $this->connectionRepository->getById($id);
        $proxy = $account->getRandomFirstProxy();

        $data = Response::getTrackingId(Api::profile($account->login, $account->password, $proxy)->getProfile($connection->entityUrn), $connection->entityUrn);

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function sendInvitation(Request $request, $id): JsonResponse
    {
        $account = Auth::user()->account;

        $proxy = $account->getRandomFirstProxy();

        $connection = $this->connectionRepository->getById($id);

        $data = Api::invitation($account->login, $account->password, $proxy)->sendInvitation($connection->entityUrn, $request->get('trackingId'), $request->get('message'));

        if ($data['status'] === 201) {

            $this->connectionRequestRepository->store([
                'account_id' => $account->id,
                'connection_id' => $connection->id,
                'user_id' => Auth::id(),
                'message' => $request->get('message')
            ]);

            return response()->json([]);
        }

        return response()->json([], 411);
    }


    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function createConversation(Request $request, $id): JsonResponse
    {
        $account = Auth::user()->account;

        $proxy = $account->getRandomFirstProxy();

        $connection = $this->connectionRepository->getById($id);

        $data = Response::newConversation((array)Api::conversation($account->login, $account->password, $proxy)->createConversation($request->get('message'), $connection->entityUrn));

        $data['account_id'] = $account->id;
        $data['connection_id'] = $connection->id;


        if ($data['success']) {
            $this->conversationRepository->updateOrCreate([
                'entityUrn' => $data['entityUrn'],
                'account_id' => $data['account_id'],
                'connection_id' => $data['connection_id']
            ], Arr::except($data, 'success'));
            return response()->json($data);
        }

        return response()->json([], 411);
    }

}
