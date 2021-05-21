<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\ConnectionRequestRepository;
use App\Repositories\KeyRepository;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{

    protected $connectionRepository;

    protected $accountRepository;

    protected $keyRepository;

    protected $connectionRequestRepository;

    /**
     * ConnectionController constructor.
     * @param ConnectionRepository $connectionRepository
     * @param AccountRepository $accountRepository
     * @param KeyRepository $keyRepository
     * @param ConnectionRequestRepository $connectionRequestRepository
     */

    public function __construct(ConnectionRepository $connectionRepository, AccountRepository $accountRepository, KeyRepository $keyRepository, ConnectionRequestRepository $connectionRequestRepository)
    {
        $this->connectionRepository = $connectionRepository;
        $this->accountRepository = $accountRepository;
        $this->keyRepository = $keyRepository;
        $this->connectionRequestRepository = $connectionRequestRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $filterAttributes = ['account', 'name'];
        $enableKeysIdes = Auth::user()->keys()->pluck('id')->toArray();
        $accounts = $this->accountRepository->selectForSelect2('full_name');
        $keys = $this->keyRepository->selectForSelect2('name',$enableKeysIdes);
        $connections = $this->connectionRepository->filter($request->all(),[],'entityUrn');

        return view('dashboard.connections.index', compact('connections', 'filterAttributes', 'keys', 'accounts'));
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
        $connection = $this->connectionRepository->getById($id);
        $data = Response::getTrackingId(Api::profile($account->login, $account->password)->getProfile($connection->publicIdentifier), $connection->publicIdentifier);

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

        $connection = $this->connectionRepository->getById($id);

        $data = Api::invitation($account->login, $account->password)->sendInvitation($connection->publicIdentifier, $request->get('trackingId'), $request->get('message'));

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




}
