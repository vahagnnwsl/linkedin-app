<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ConnectionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConnectionStatusRequest;
use App\Http\Requests\StatusRequest;
use App\Jobs\Connections\CalcExperience;
use App\Jobs\Connections\GetConnectionCareerInterest;
use App\Jobs\Connections\GetConnectionPositions;
use App\Jobs\Connections\GetConnectionsCareerInterest;
use App\Jobs\Connections\GetConnectionSkills;
use App\Jobs\Connections\GetConnectionsPositions;
use App\Jobs\Connections\GetConnectionsSkills;
use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Models\Search;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\ConnectionRequestRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\KeyRepository;
use App\Repositories\UserRepository;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

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
     * @param $request
     * @return array
     */
    public function prepareGetAll($request, $paginate = true): array
    {

        $user = Auth::user();
        $userAccount = Auth::user()->account;

        if ($user->role->name === UserRepository::$ADMIN_ROLE) {
            $enableKeysIds = $this->keyRepository->query()->pluck('keys.id')->toArray();
        } else {
            $enableKeysIds = Auth::user()->keys()->pluck('keys.id')->toArray();
        }


        $request['statuses'] = $request['statuses'] ?? 'all';
        $request['positions'] = $request['positions'] ?? 'all';
        $request['distance'] = $request['distance'] ?? 'all';
        $request['connections_keys'] = $request['connections_keys'] ?? 'all';
        $request['accountsIds'] = Auth::user()->unRealAccounts()->pluck('accounts.id')->toArray();
        $request['enableKeysIdes'] = $enableKeysIds;
        $request['sortBy'] = $request['sortBy'] ?? 'DESC';
        $request['sortColumn']  = $request['sortColumn'] ?? 'id';

        if ($userAccount) {
            array_push($request['accountsIds'], $userAccount->id);
        }
        $companies = [];

        if (isset($request['companies']) && count($request['companies']) > 0) {
            $companies = $this->companyRepository->getByIds($request['companies']);
        }

        $keys = $this->keyRepository->query()->whereIn('keys.id', $enableKeysIds)->get();
        $connections = $this->connectionRepository->filter($request, $user, $paginate);

        return [
            'connections' => $connections,
            'keys' => $keys,
            'companies' => $companies,
            'userAccount' => $userAccount,
        ];
    }

    public function index(Request $request)
    {


        $req = $request->all();
        $hash = '';

        if (isset($req['hash'])) {
            $search = Search::where(['hash' => $req['hash']])->first();
            if (!$search) return redirect()->route('connections.index');
            $req = $search->params;
            $hash = $search->hash;
        }

        $data = $this->prepareGetAll($req);

        $connections = $data['connections'];

        $connections->load('accounts', 'keys', 'requests', 'requests.account','threads.account');

        $keys = $data['keys'];
        $userAccount = $data['userAccount'];
        $companies = $data['companies'];

        $categories = $this->connectionRepository->getCategories();
        $accounts = $this->accountRepository->getAll();

        if (Auth::user()->role->name === UserRepository::$ADMIN_ROLE) {
            $searches = Search::orderBy('created_at', 'desc')->get();
        } else {
            $searches = Search::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        }

        return view('dashboard.connections.index', compact('req', 'hash', 'connections', 'accounts', 'categories', 'keys', 'userAccount', 'companies', 'searches'));
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
        $categories = $this->categoryRepository->getParentsWithChild(true);
        $keys = $this->keyRepository->getAll();

        return view('dashboard.connections.edit', compact('connection', 'categories', 'keys'));
    }

    public function fullInfo(int $id): JsonResponse
    {
        $connection = $this->connectionRepository->getById($id);
        $account = Auth::user()->account;
        $proxy = $account->proxy;
        if (!$proxy) {
            return \response()->json(['msg' => 'Account has not proxy'], 401);
        }

        $success = $this->check($proxy);
        if (!$success) {
            return \response()->json(['msg' => 'Invalid proxy'], 401);
        }
        $resp = Api::profile($account)->getOwnProfile();
        if ($resp['status'] !== 200) {
            return \response()->json(['msg' => 'Invalid cookie'], 401);
        }

        $skills = Api::profile($account)->getProfileSkills($connection->entityUrn);
        $profile = Api::profile($account)->getProfile($connection->entityUrn);
        $opportunity = Api::profile($account)->getOpportunityCards($connection->entityUrn);
        $skills = \App\Linkedin\Responses\Connection::parseOnlyGroupBy($skills);
        $profile = \App\Linkedin\Responses\Connection::parseOnlyGroupBy($profile);
        $opportunity = \App\Linkedin\Responses\Connection::parseOnlyGroupBy($opportunity);
        return \response()->json(['skills' => $skills, 'profile' => $profile, 'opportunity' => $opportunity]);
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

    /// onliy connection have not account and hav not send account
    public function carrierInterest(): RedirectResponse
    {
        $account = Auth::user()->account;
        GetConnectionsCareerInterest::dispatch($account);
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
        GetConnectionCareerInterest::dispatch($account, $connection);
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
     * @param StatusRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function addStatus(ConnectionStatusRequest $request, int $id): RedirectResponse
    {
        $data = [
            "morphedModel" => Auth::user()->id,
            "morphClass" => class_basename(User::class),
            "text" =>$request->get('text'),
            "categories" => $request->get('categories'),
        ];

        $this->connectionRepository->addStatus($id, $data);
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

    public function exportCvs(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $searchKey = '';
        $searchParams = [];

        if ($request->has('hash') && $request->get('hash')) {
            $search = Search::where(['hash' => $request->hash])->first();
            $searchParams = Arr::except($search->params, ['page', 'hash']);
            $searchKey = $search->name;
        } else {
            if (count($request->all())) {
                $searchParams = Arr::except($request->all(), ['page', 'hash']);
                $searchKey = Arr::dot($request->all());
            }
        }


        $data = $this->prepareGetAll($searchParams, false);


        return Excel::download(new ConnectionExport($data['connections'], $searchKey), date('d-m-Y') . '-connections.xlsx');

    }
}
