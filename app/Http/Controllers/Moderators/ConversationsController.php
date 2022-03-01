<?php

namespace App\Http\Controllers\Moderators;

use App\Http\Controllers\Controller;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ConversationsController extends Controller
{

    /**
     * @var MessageRepository
     */
    private $messageRepository;
    /**
     * @var ConversationRepository
     */
    private $conversationRepository;

    public function __construct(MessageRepository $messageRepository, ConversationRepository $conversationRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->conversationRepository = $conversationRepository;
    }

    public function index()
    {
        return view('moderators.welcome');
    }

    public function conversation($id)
    {
        $conversation = $this->conversationRepository->getByEntityUrn($id);
        if (!$conversation) return redirect(route('moderators.conversations.index'));
        $messages = $this->messageRepository->getMessagesAllByConversationId($conversation->id);
        return view('moderators.conversations',compact('messages'));

    }
}
