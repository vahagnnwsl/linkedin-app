<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\MessageCollection;
use App\Jobs\SyncLastMessagesForOneAccount;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        $messages = $this->conversationRepository->getMessages($id, $request->get('start'));

        return response()->json(['messages' => new MessageCollection(collect($messages)->sortBy('date'))]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function synLastMessages($id): JsonResponse
    {
        $account = Auth::user()->account;

        SyncLastMessagesForOneAccount::dispatch($account->id, $id, Auth::id());

        return response()->json([]);
    }


}
