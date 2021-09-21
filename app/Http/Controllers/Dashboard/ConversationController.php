<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\ConversationCollection;
use App\Http\Resources\Collections\MessageCollection;
use App\Http\Resources\ConversationResource;
use App\Jobs\Conversations\GetConversationMessages;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{

    protected ConversationRepository $conversationRepository;

    protected MessageRepository $messageRepository;

    /**
     * PermissionController constructor.
     * @param ConversationRepository $conversationRepository
     * @param MessageRepository $messageRepository
     */

    public function __construct(ConversationRepository $conversationRepository, MessageRepository $messageRepository)
    {
        $this->conversationRepository = $conversationRepository;

        $this->messageRepository = $messageRepository;
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function synLastMessages($id): JsonResponse
    {
        $account = Auth::user()->account;

        $conversation = $this->conversationRepository->getById($id);

        GetConversationMessages::dispatch(Auth::user(), $account, $conversation);

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
    public function getByAccount(int $account_id,Request $request): JsonResponse
    {
        $conversations = new ConversationCollection($this->conversationRepository->getByAccountId($account_id, $request->get('start'), $request->get('key'),$request->get('distance')));
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

        $messages = $this->conversationRepository->getMessages($conversation->id, $request->get('start'));

//        if ($request->get('relative')) {
//            $relatedAccountsIdes = Auth::user()->unRealAccounts()->pluck('accounts.id')->toArray();
//            $conversation = $this->conversationRepository->getById($id);
//            $relatedConversations = new ConversationCollection($this->conversationRepository->getConnectionConversationsByConnectionAndAccount($conversation->connection_id, $relatedAccountsIdes));
//
//        }

        return response()->json(['messages' => new MessageCollection(collect($messages)->sortBy('date')), 'relatedConversations' => $relatedConversations]);
    }

}
