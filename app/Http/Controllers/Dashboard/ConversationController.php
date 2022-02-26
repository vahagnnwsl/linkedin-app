<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\ConversationCollection;
use App\Http\Resources\Collections\MessageCollection;
use App\Http\Resources\ConversationResource;
use App\Jobs\Conversations\GetConversationMessages;
use App\Repositories\AccountRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{

    protected ConversationRepository $conversationRepository;

    protected MessageRepository $messageRepository;

    protected AccountRepository $accountRepository;

    /**
     * @param ConversationRepository $conversationRepository
     * @param MessageRepository $messageRepository
     * @param AccountRepository $accountRepository
     */
    public function __construct(ConversationRepository $conversationRepository, MessageRepository $messageRepository, AccountRepository $accountRepository)
    {
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
        $this->accountRepository = $accountRepository;
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function synLastMessages($id,Request $request): JsonResponse
    {
        $account = Auth::user()->account;
        $conversation = $this->conversationRepository->getById($id);


        if ($request->has('account_id')) {
            $account = $this->accountRepository->getById((int)$request->get('account_id'));
        }


        GetConversationMessages::dispatch(Auth::user(), $account, $conversation, true);

        return response()->json([]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $conversation = $this->conversationRepository->getById($id);
        return response()->json(['conversation' => new ConversationResource($conversation)]);
    }

    /**
     * @param string $hash
     * @return JsonResponse
     */
    public function getByEntityUrn(string $hash): JsonResponse
    {
        $conversation = $this->conversationRepository->getByEntityUrn($hash);
        return response()->json(['conversation' => new ConversationResource($conversation)]);
    }

    /**
     * @param int $account_id
     * @param Request $request
     * @return JsonResponse
     */
    public function getByAccount(int $account_id, Request $request): JsonResponse
    {
        $conversations = new ConversationCollection($this->conversationRepository->getByAccountId($account_id, $request->get('start'), $request->get('key'), $request->get('distance'), $request->get('condition')));
        return response()->json(['conversations' => $conversations]);
    }


    /**
     * @param Request $request
     * @param string $hash
     * @return JsonResponse
     */
    public function getMessages(Request $request, string $hash): JsonResponse
    {

        $relatedConversations = [];
        $conversation = $this->conversationRepository->getByEntityUrn($hash);
        $users = [
            'connection' => $conversation->connection,
            'account' => $conversation->account,
        ];

        if ($request->has('type') && $request->get('type') === 'all') {
            $messages = $this->conversationRepository->getAllMessages($conversation->id,'ASC');
            $messages= new MessageCollection($messages);

        } else {
            $messages = $this->conversationRepository->getMessages($conversation->id, $request->get('start'));
            $messages= new MessageCollection(collect($messages)->sortBy('date'));
        }



        return response()->json([
            'messages' => $messages,
            'relatedConversations' => $relatedConversations,
            'users' => $users,
            'conversation' => $conversation,
        ]);
    }

}
