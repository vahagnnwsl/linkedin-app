<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Http\Resources\Collections\ConversationCollection;
use App\Jobs\SyncConnectionsForOneAccount;
use App\Jobs\SyncConversationsForOneAccount;
use App\Repositories\AccountRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class AccountController extends Controller
{

    protected $accountRepository;

    protected $conversationRepository;

    protected $messageRepository;


    /**
     * AccountController constructor.
     * @param AccountRepository $accountRepository
     * @param ConversationRepository $conversationRepository
     * @param MessageRepository $messageRepository
     */

    public function __construct(AccountRepository $accountRepository, ConversationRepository $conversationRepository, MessageRepository $messageRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
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

        return view('dashboard.accounts.create');
    }


    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {

        $account = $this->accountRepository->getById($id);
        if (!$account) {
            abort(404);
        }

        return view('dashboard.accounts.edit', compact('account'));
    }


    /**
     * @param AccountRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(AccountRequest $request, int $id): RedirectResponse
    {

        $this->accountRepository->update($id, $request->validated());
        $this->putFlashMessage(true, 'Successfully updated');
        return redirect()->route('accounts.edit', $id);
    }


    /**
     * @param AccountRequest $request
     * @return RedirectResponse
     */
    public function store(AccountRequest $request): RedirectResponse
    {

        $this->accountRepository->store($request->validated());
        $this->putFlashMessage(true, 'Successfully created');
        return redirect()->route('accounts.index');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function syncConversations($id): RedirectResponse
    {

        SyncConversationsForOneAccount::dispatch($id);

        $this->putFlashMessage(true, 'Your request on process');

        return redirect()->back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function syncConnections($id): RedirectResponse
    {

        SyncConnectionsForOneAccount::dispatch($id);

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
        $conversations = new ConversationCollection($this->conversationRepository->getByAccountId($id, $request->get('start'),$request->get('key')));
        return response()->json(['conversations' => $conversations]);
    }

    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function conversations(int $id)
    {

        $conversations = $this->accountRepository->getConversations($id);
        return view('dashboard.accounts.conversations', compact('conversations', 'id'));
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

        return view('dashboard.accounts.messages', compact('messages', 'account','conversation'));
    }
}
