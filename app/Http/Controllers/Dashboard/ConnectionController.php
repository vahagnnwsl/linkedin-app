<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusRequest;
use App\Jobs\Connections\CalcExperience;
use App\Jobs\Connections\GetConnectionPositions;
use App\Jobs\Connections\GetConnectionSkills;
use App\Jobs\Connections\GetConnectionsPositions;
use App\Jobs\Connections\GetConnectionsSkills;
use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Repositories\AccountRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\ConnectionRequestRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\KeyRepository;
use App\Repositories\UserRepository;
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

    protected ConnectionRepository $connectionRepository;

    protected AccountRepository $accountRepository;

    protected KeyRepository $keyRepository;

    protected ConversationRepository $conversationRepository;

    protected ConnectionRequestRepository $connectionRequestRepository;

    protected CategoryRepository $categoryRepository;

    protected CompanyRepository $companyRepository;

    /**
     * ConnectionController constructor.
     * @param ConnectionRepository $connectionRepository
     * @param AccountRepository $accountRepository
     * @param KeyRepository $keyRepository
     * @param ConnectionRequestRepository $connectionRequestRepository
     * @param ConversationRepository $conversationRepository
     * @param CategoryRepository $categoryRepository
     * @param CompanyRepository $companyRepository
     */

    public function __construct(ConnectionRepository        $connectionRepository,
                                AccountRepository           $accountRepository,
                                KeyRepository               $keyRepository,
                                ConnectionRequestRepository $connectionRequestRepository,
                                ConversationRepository      $conversationRepository,
                                CategoryRepository          $categoryRepository,
                                CompanyRepository           $companyRepository
    )
    {
        $this->connectionRepository = $connectionRepository;
        $this->accountRepository = $accountRepository;
        $this->keyRepository = $keyRepository;
        $this->connectionRequestRepository = $connectionRequestRepository;
        $this->conversationRepository = $conversationRepository;
        $this->categoryRepository = $categoryRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userAccount = Auth::user()->account;

        if ($user->role->name === UserRepository::$ADMIN_ROLE) {
            $enableKeysIds = $this->keyRepository->query()->pluck('keys.id')->toArray();
        } else {
            $enableKeysIds = Auth::user()->keys()->pluck('keys.id')->toArray();
        }


        $data = $request->all();

        $data['statuses'] = $data['statuses'] ?? 'all';
        $data['positions'] = $data['positions'] ?? 'all';
        $data['distance'] = $data['distance'] ?? 'all';
        $data['connections_keys'] = $data['connections_keys'] ?? 'all';
        $data['accountsIds'] = Auth::user()->unRealAccounts()->pluck('accounts.id')->toArray();
        $data['enableKeysIdes'] = $enableKeysIds;

        if ($userAccount) {
            array_push($data['accountsIds'], $userAccount->id);
        }
        $companies = [];

        if ($request->has('companies')) {
            $companies = $this->companyRepository->getByIds($request->get('companies'));
        }

        $keys = $this->keyRepository->query()->whereIn('keys.id', $enableKeysIds)->get();
        $connections = $this->connectionRepository->filter($data, $user);
        $connections->load('accounts', 'keys', 'requests', 'requests.account');
        $categories = $this->connectionRepository->getCategories();
        $accounts = $this->accountRepository->getAll();

        return view('dashboard.connections.index', compact('connections', 'accounts', 'categories', 'keys', 'userAccount',  'companies'));
    }

    /**
     * @param int $id
     */
    public function edit(int $id)
    {
        $connection = $this->connectionRepository->getById($id);
        $connection->load('positions');
        $connection->load('positions.company');
        $connection->load('skills');
        $connection->load('statuses');
        $connection->load('statuses.category');
        $categories = $this->categoryRepository->getAll();
        $keys = $this->keyRepository->getAll();

        return view('dashboard.connections.edit', compact('connection', 'categories', 'keys'));
    }


    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getInfo(int $id): JsonResponse
    {

        $connection = $this->connectionRepository->getById($id);
        $connection->load('positions');
        $connection->load('positions.company');
        $connection->load('skills');

        return response()->json([
            'fullName' => $connection->fullName,
            'positions' => $connection->positions,
            'skills' => $connection->skills
        ]);
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

        $data = Response::getTrackingId(Api::profile($account)->getProfile($connection->entityUrn), $connection->entityUrn);

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function sendRequest(Request $request, $id): JsonResponse
    {
        $account = Auth::user()->account;

        $connection = $this->connectionRepository->getById($id);

        $data = Response::getTrackingId(Api::profile($account)->getProfile($connection->entityUrn), $connection->entityUrn);

        if ($data['success']) {
            $data = Api::invitation($account)->sendInvitation($connection->entityUrn, $data['trackingId'], $request->get('message'));
            if ($data['status'] === 201) {
                $this->connectionRequestRepository->store([
                    'account_id' => $account->id,
                    'connection_id' => $connection->id,
                    'user_id' => Auth::id(),
                    'date' => date('Y-m-d  H:m'),
                    'message' => $request->get('message')
                ]);

                return response()->json([]);
            }
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

        $data = Response::newConversation((array)Api::conversation($account, $proxy)->createConversation($request->get('message'), $connection->entityUrn));

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

    /**
     * @return RedirectResponse
     */
    public function getSkills(): RedirectResponse
    {
        $account = Auth::user()->account;
        GetConnectionsSkills::dispatch($account);
        $this->putFlashMessage(true, 'Successfully run job');

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function getPositions(): RedirectResponse
    {
        $account = Auth::user()->account;
        GetConnectionsPositions::dispatch($account);
        $this->putFlashMessage(true, 'Successfully run job');

        return redirect()->back();
    }


    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function getSkillsAndPositions(int $id): RedirectResponse
    {
        $account = Auth::user()->account;
        $connection = $this->connectionRepository->getById($id);
        GetConnectionPositions::dispatch($account, $connection);
        GetConnectionSkills::dispatch($account, $connection);
        $this->putFlashMessage(true, 'Successfully run job');
        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function calcExperience(): RedirectResponse
    {
        CalcExperience::dispatch();
        $this->putFlashMessage(true, 'Successfully run job');
        return redirect()->back();
    }

    /**
     * @param StatusRequest $statusRequest
     * @param int $id
     * @return RedirectResponse
     */
    public function addStatus(StatusRequest $statusRequest, int $id): RedirectResponse
    {
        $this->connectionRepository->addStatus($statusRequest->validated(), $id);
        $this->putFlashMessage(true, 'Successfully added');
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function addKeys(Request $request, int $id): RedirectResponse
    {
        $this->connectionRepository->addKeys($id, $request->get('keys'));
        $this->putFlashMessage(true, 'Successfully added');
        return redirect()->back();
    }
}
