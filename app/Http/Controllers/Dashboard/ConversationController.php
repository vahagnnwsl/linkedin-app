<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\ConversationCollection;
use App\Http\Resources\Collections\MessageCollection;
use App\Jobs\GetConversationMessages;
use App\Jobs\GetLastMessagesConversation;
use App\Jobs\SyncLastMessagesForOneAccount;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{

    protected $conversationRepository;

    protected $messageRepository;

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
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getMessages(Request $request, int $id): JsonResponse
    {

        $relatedConversations = [];

        $messages = $this->conversationRepository->getMessages($id, $request->get('start'));

        if ($request->get('relative')) {
            $relatedAccountsIdes = Auth::user()->unRealAccounts()->pluck('accounts.id')->toArray();
            $conversation = $this->conversationRepository->getById($id);
            $relatedConversations = new ConversationCollection($this->conversationRepository->getConnectionConversationsByConnectionAndAccount($conversation->connection_id, $relatedAccountsIdes));

        }

        return response()->json(['messages' => new MessageCollection(collect($messages)->sortBy('date')), 'relatedConversations' => $relatedConversations]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function synLastMessages($id): JsonResponse
    {
        $account = Auth::user()->account;

        $conversation = $this->conversationRepository->getById($id);

        GetConversationMessages::dispatch( Auth::user(),$account, $conversation);

        return response()->json([]);
    }


}
