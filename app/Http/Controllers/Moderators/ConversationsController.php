<?php

namespace App\Http\Controllers\Moderators;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Moderator;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use App\Repositories\ModeratorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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

    /**
     * @var ModeratorRepository
     */
    private $moderatorRepository;

    public function __construct(MessageRepository $messageRepository, ConversationRepository $conversationRepository, ModeratorRepository $moderatorRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->conversationRepository = $conversationRepository;
        $this->moderatorRepository = $moderatorRepository;
    }

    public function index(Request $request)
    {
        $conversationCount = $this->conversationRepository->getCount();
        $moderatorCount = $this->moderatorRepository->getCount();

        $mod = $this->moderatorRepository->getById(Auth::guard('moderator')->user()->id);

        $perPage = 20;
        $total =  (int)round($conversationCount / $moderatorCount,-1);


        dd($mod);
        $enableMinOffset = ceil($conversationCount / $moderatorCount * ($mod->position-1));

        $offset = $request->has('offset') ? $request->get('offset') : $enableMinOffset;
        $enableMaxOffset = ceil($conversationCount / $moderatorCount - 15);
        dump($enableMaxOffset, $offset,$mod->position);

        if ($offset > $enableMaxOffset) $offset = $enableMinOffset;


        $conversations = DB::table('conversations')->offset($offset)->take(15)->get();
        return view('moderators.welcome',compact('conversations', 'total', 'perPage'));
    }

    public function conversation($id)
    {

        $conversation = $this->conversationRepository->getByEntityUrn($id);
        if (!$conversation) return redirect(route('moderators.conversations.index'));
        $messages = $this->messageRepository->getMessagesAllByConversationId($conversation->id);
        return view('moderators.conversations', compact('messages'));

    }
}
