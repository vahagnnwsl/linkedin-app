<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Http\Resources\Collections\ConversationCollection;
use App\Jobs\Account\GetConnections;
use App\Jobs\Account\GetConversations;
use App\Jobs\Account\Pm2Ecosystem;
use App\Jobs\Conversations\GetConversationsLastMessages;
use App\Jobs\Conversations\GetConversationsMessages;
use App\Jobs\Pm2\DeletePid;
use App\Jobs\Pm2\StartPid;
use App\Jobs\Pm2\StopPid;
use App\Jobs\SyncRequestsJob;
use App\Linkedin\Api;
use App\Linkedin\Responses\Connection;
use App\Linkedin\Responses\Cookie;
use App\Linkedin\Responses\Invitation;
use App\Models\Conversation;
use App\Models\Log;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRequestRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use App\Repositories\ProxyRepository;
use App\Services\ConnectionService;
use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AccountController extends Controller
{

    /**
     * @var AccountRepository
     */
    protected AccountRepository $accountRepository;

    /**
     * @var ConversationRepository
     */
    protected ConversationRepository $conversationRepository;

    /**
     * @var MessageRepository
     */
    protected MessageRepository $messageRepository;

    /**
     * @var ProxyRepository
     */
    protected ProxyRepository $proxyRepository;

    /**
     * @var ConnectionService
     */
    protected ConnectionService $connectionService;

    /**
     * @var ConnectionRequestRepository
     */
    protected $connectionRequestRepository;


    /**
     * @param AccountRepository $accountRepository
     * @param ConversationRepository $conversationRepository
     * @param MessageRepository $messageRepository
     * @param ProxyRepository $proxyRepository
     * @param ConnectionService $connectionService
     */

    public function __construct(AccountRepository $accountRepository, ConversationRepository $conversationRepository, MessageRepository $messageRepository, ProxyRepository $proxyRepository, ConnectionService $connectionService, ConnectionRequestRepository $connectionRequestRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
        $this->proxyRepository = $proxyRepository;
        $this->connectionService = $connectionService;
        $this->connectionRequestRepository = $connectionRequestRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $accounts = $this->accountRepository->paginate();
        return view('dashboard.accounts.index', compact('accounts'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $proxies = $this->proxyRepository->selectForSelect2('ip');
        return view('dashboard.accounts.create', compact('proxies'));
    }


    /**
     * @param int $id
     * @return Factory|View|Application
     */
    public function getRequests(int $id)
    {
        $account = $this->accountRepository->getById($id);

        $resp = Api::profile($account)->getOwnProfile();
        if ($resp['status'] === 200) {
            $this->connectionService->getAccountRequest($account);
        } else {
            $this->putFlashMessage(false, 'Invalid cookie');
        }

        $requests = $this->connectionRequestRepository->model()::where('connection_requests.account_id', $id)->paginate(20);
        $requests->load('connection', 'connection.accounts', 'connection.keys');

        return view('dashboard.accounts.requests', compact('account', 'requests'));
    }

    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $proxies = $this->proxyRepository->selectForSelect2('ip');

        $account = $this->accountRepository->getById($id);
        if (!$account) {
            abort(404);
        }


        return view('dashboard.accounts.edit', compact('account', 'proxies'));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $account = $this->accountRepository->getById($id);
        return response()->json(['account' => $account]);
    }


    /**
     * @param AccountRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(AccountRequest $request, int $id): RedirectResponse
    {

        $data = $request->validated();
        if ($data['cookie_web_str']) {
            try {
                $data['jsessionid'] = Cookie::getJsessionid($data['cookie_web_str']);
            } catch (\Exception $exception) {
                $this->putFlashMessage(false, 'Invalid cookie string');
                return redirect()->route('accounts.edit', $id);
            }
        }

        $this->accountRepository->update($id, $data);


        $this->putFlashMessage(true, 'Successfully updated');


        return redirect()->route('accounts.edit', $id);
    }


    /**
     * @param AccountRequest $request
     * @return RedirectResponse
     */
    public function store(AccountRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = 0;
        $this->accountRepository->store($data);
        $this->putFlashMessage(true, 'Successfully created');
        return redirect()->route('accounts.index');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function syncConversations($id): RedirectResponse
    {
        $account = $this->accountRepository->getById($id);

        $proxy = $account->proxy;
        if (!$proxy) {
            $this->putFlashMessage(false, 'Account has not proxy');
            return redirect()->back();
        }

        $success = $this->check($proxy);
        if (!$success) {
            $this->putFlashMessage(false, 'Invalid proxy');
            return redirect()->back();
        }
        $resp = Api::profile($account)->getOwnProfile();
        if ($resp['status'] !== 200) {
            $this->putFlashMessage(false, 'Invalid cookie');
            return redirect()->back();
        }

        GetConversations::dispatch($account);
        $this->putFlashMessage(true, 'Your request on process');
        return redirect()->back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function syncConnections($id): RedirectResponse
    {

        $account = $this->accountRepository->getById($id);
        $proxy = $account->proxy;
        if (!$proxy) {
            $this->putFlashMessage(false, 'Account has not proxy');
            return redirect()->back();
        }

        $success = $this->check($proxy);
        if (!$success) {
            $this->putFlashMessage(false, 'Invalid proxy');
            return redirect()->back();
        }
        $resp = Api::profile($account)->getOwnProfile();
        if ($resp['status'] !== 200) {
            $this->putFlashMessage(false, 'Invalid cookie');
            return redirect()->back();
        }
        GetConnections::dispatch($account);
        $this->putFlashMessage(true, 'Your request on process');

        return redirect()->back();
    }


    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function syncConversationsMessages(int $id, Request $request): RedirectResponse
    {

        $limit = $request->get('limit') || null;

        $account = $this->accountRepository->getById($id);
        $proxy = $account->proxy;
        if (!$proxy) {
            $this->putFlashMessage(false, 'Account has not proxy');
            return redirect()->back();
        }

        $success = $this->check($proxy);
        if (!$success) {
            $this->putFlashMessage(false, 'Invalid proxy');
            return redirect()->back();
        }
        $resp = Api::profile($account)->getOwnProfile();
        if ($resp['status'] !== 200) {
            $this->putFlashMessage(false, 'Invalid cookie');
            return redirect()->back();
        }
        GetConversationsMessages::dispatch(Auth::user(), $account, $limit);
        $this->putFlashMessage(true, 'Your request on process');
        return redirect()->back();
    }

    public function syncConversationsLastMessages(int $id): RedirectResponse
    {
        $account = $this->accountRepository->getById($id);
        $proxy = $account->proxy;
        if (!$proxy) {
            $this->putFlashMessage(false, 'Account has not proxy');
            return redirect()->back();
        }

        $success = $this->check($proxy);
        if (!$success) {
            $this->putFlashMessage(false, 'Invalid proxy');
            return redirect()->back();
        }
        $resp = Api::profile($account)->getOwnProfile();
        if ($resp['status'] !== 200) {
            $this->putFlashMessage(false, 'Invalid cookie');
            return redirect()->back();
        }
        GetConversationsLastMessages::dispatch(Auth::user(), $account);
        $this->putFlashMessage(true, 'Your request on process');
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getConversations(Request $request, int $id): JsonResponse
    {
        $conversations = new ConversationCollection($this->conversationRepository->getByAccountId($id, $request->get('start'), $request->get('key')));
        return response()->json(['conversations' => $conversations]);
    }

    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function conversations(int $id)
    {
        $account = $this->accountRepository->getById($id);

        return view('dashboard.accounts.conversations', compact('account'));
    }

    /**
     * @param $account_id
     * @param $conversation_id
     * @return Application|Factory|View
     */
    public function conversationMessages($account_id, $conversation_id)
    {

        $messages = $this->messageRepository->getMessagesByConversationId($conversation_id);

        $account = $this->accountRepository->getById($account_id);
        $conversation = $this->conversationRepository->getById($conversation_id);

        return view('dashboard.accounts.messages', compact('messages', 'account', 'conversation'));
    }

    public function syncRequests(int $id): RedirectResponse
    {
        $account = $this->accountRepository->getById($id);
        $proxy = $account->proxy;
        if (!$proxy) {
            $this->putFlashMessage(false, 'Account has not proxy');
            return redirect()->back();
        }

        $success = $this->check($proxy);
        if (!$success) {
            $this->putFlashMessage(false, 'Invalid proxy');
            return redirect()->back();
        }
        $resp = Api::profile($account)->getOwnProfile();
        if ($resp['status'] !== 200) {
            $this->putFlashMessage(false, 'Invalid cookie');
            return redirect()->back();
        }
        SyncRequestsJob::dispatch($account);
        $this->putFlashMessage(true, 'Your request on process');

        return redirect()->back();
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function checkLife(int $id): JsonResponse
    {
        $account = $this->accountRepository->getById($id);
        $proxy = $account->proxy;
        if (!$proxy) {
            return response()->json(['life' => 'Account has not proxy']);
        }

        $success = $this->check($proxy);
        if (!$success) {
            return response()->json(['life' => 'Invalid proxy']);
        }
        $resp = Api::profile($account)->getOwnProfile();
        $life = false;
        if ($resp['status'] === 200) {
            $resp = Connection::parseSingle((array)$resp['data']);
            $account->update($resp);
            $life = true;
        }

        return response()->json(['life' => $life]);

    }

    /**
     * @return JsonResponse
     */
    public function checkAllLife(): JsonResponse
    {
        $accounts = $this->accountRepository->model()::where([
            'status' => $this->accountRepository::$ACTIVE_STATUS,
            'type' => $this->accountRepository::$TYPE_REAL,
        ])->get();

        $resp = $accounts->map(function ($account) {
            $proxy = $account->proxy;

            if (!$proxy) {
                return [
                    'id' => $account->id,
                    'success' => false,
                    'life' => 'Account has not proxy',
                ];
            }

            $success = $this->check($proxy);
            if (!$success) {
                return [
                    'id' => $account->id,
                    'success' => false,
                    'life' => 'Invalid proxy',
                ];
            }

            $resp = Api::profile($account)->getOwnProfile();

            return [
                'id' => $account->id,
                'success' => $resp['status'] === 200,
                'life' => $resp['status'] === 200 ? 'Life goes on ' : 'Life does not go on',
            ];
        });

        return response()->json($resp);
    }

    /**
     * @return JsonResponse
     */
    public function checkOnline(): JsonResponse
    {
        $accounts = $this->accountRepository->getAll();

        $resp = $accounts->map(function ($account) {

            return [
                'id' => $account->id,
                'success' => $account->is_online,
                'lastActivityAt' => $account->lastActivityAt ? $account->lastActivityAt->timezone('Asia/Yerevan')->format('Y-m-d H:i:s') : '',
                'online' => $account->is_online ? 'Online' : 'Offline',
            ];
        });

        return response()->json($resp);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function setOnlineParameter(int $id, Request $request): JsonResponse
    {
        $account = $this->accountRepository->getById($id);

        $resp = [
            'success' => true,
            'msg' => 'Successfully started',
            'status' => $request->status
        ];

        if ((int)$request->status === 1) {
            if (File::exists(storage_path('linkedin/' . $account->login . '.json'))) {
                StartPid::dispatch($account);
            } else {
                $resp['msg'] = 'Please run command:login-linkedin for this account';
                $resp['success'] = false;
            }
        } else {
            StopPid::dispatch($account);
            $resp['msg'] = 'Successfully stopped';
        }
        return response()->json($resp);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->accountRepository->delete($id);
        $this->putFlashMessage(true, 'Successful');

        return redirect()->back();
    }
}
