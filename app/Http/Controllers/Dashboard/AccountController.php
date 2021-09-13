<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Http\Resources\Collections\ConversationCollection;
use App\Jobs\Account\GetConnections;
use App\Jobs\Account\GetConversations;
use App\Jobs\AccountsLogin;
use App\Jobs\SyncRequestsJob;
use App\Linkedin\Api;
use App\Linkedin\Responses\Cookie;
use App\Repositories\AccountRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use App\Repositories\ProxyRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AccountController extends Controller
{

    protected AccountRepository $accountRepository;

    protected ConversationRepository $conversationRepository;

    protected MessageRepository $messageRepository;

    protected ProxyRepository $proxyRepository;


    /**
     * AccountController constructor.
     * @param AccountRepository $accountRepository
     * @param ConversationRepository $conversationRepository
     * @param MessageRepository $messageRepository
     * @param ProxyRepository $proxyRepository
     */

    public function __construct(AccountRepository $accountRepository, ConversationRepository $conversationRepository, MessageRepository $messageRepository, ProxyRepository $proxyRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
        $this->proxyRepository = $proxyRepository;
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
     * @param AccountRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(AccountRequest $request, int $id): RedirectResponse
    {

        $data = $request->validated();
        try {
            $data['cookie_web'] = json_encode(Cookie::parsCookieForWeb($data['cookie_str']));
        } catch (\Exception $exception) {
            $this->putFlashMessage(false, 'Invalid cookie string');

            return redirect()->route('accounts.edit', $id);
        }
        try {
            $data['cookie_socket'] = json_encode(Cookie::parsCookieForSocket($data['cookie_socket_str']));
        } catch (\Exception $exception) {
            $this->putFlashMessage(false, 'Invalid socket cookie string');
            return redirect()->route('accounts.edit', $id);
        }

        $this->accountRepository->update($id, Arr::except($data, 'proxies_id'));

        $this->accountRepository->syncProxies($id, $data['proxies_id']);

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
        try {
            $data['cookie_web'] = json_encode(Cookie::parsCookieForWeb($data['cookie_str']));
        } catch (\Exception $exception) {
            $this->putFlashMessage(false, 'Invalid web cookie string');
            return redirect()->back();
        }

        try {
            $data['cookie_socket'] = json_encode(Cookie::parsCookieForSocket($data['cookie_socket_str']));
        } catch (\Exception $exception) {
            $this->putFlashMessage(false, 'Invalid socket cookie string');
            return redirect()->back();
        }

        $proxy = $this->accountRepository->store(Arr::except($data, 'proxies_id'));
        $this->accountRepository->syncProxies($proxy->id, $data['proxies_id']);
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
        GetConnections::dispatch($account);
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
        SyncRequestsJob::dispatch($account);
        $this->putFlashMessage(true, 'Your request on process');

        return redirect()->back();
    }

    public function login(int $type): RedirectResponse
    {
        AccountsLogin::dispatch($type);
        $this->putFlashMessage(true, 'Your request on process');
        return redirect()->back();
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function checkLife(int $id): RedirectResponse
    {
        $account = $this->accountRepository->getById($id);
        $resp = Api::profile($account->login, $account->password)->getOwnProfile();
        if ($resp['status'] === 200) {
            $this->putFlashMessage(true, 'Life goes on ');
        } else {
            $this->putFlashMessage(false, 'Life does not go on');
        }
        return redirect()->back();

    }

    /**
     * @return JsonResponse
     */
    public function checkAllLife(): JsonResponse
    {
        $accounts = $this->accountRepository->getAllRealAccounts();

        $resp = $accounts->map(function ($account) {
            $resp = Api::profile($account->login, $account->password)->getOwnProfile();
            return [
                'id' => $account->id,
                'success' => $resp['status'] === 200,
                'life' => $resp['status'] === 200 ? 'Life goes on ' : 'Life does not go on',
            ];
        });

        return response()->json($resp);
    }

}
