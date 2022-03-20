<?php

namespace App\Http\Controllers\Moderators;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConnectionRequest;
use App\Http\Requests\ConnectionStatusRequest;
use App\Models\Conversation;
use App\Models\Moderator;
use App\Repositories\CategoryRepository;
use App\Repositories\ConnectionRepository;
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

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var ConnectionRepository
     */

    /**
     * @var ConnectionRepository
     */
    private $connectionRepository;

    public function __construct(
        MessageRepository      $messageRepository,
        ConversationRepository $conversationRepository,
        ModeratorRepository    $moderatorRepository,
        CategoryRepository     $categoryRepository,
        ConnectionRepository $connectionRepository
    )
    {
        $this->messageRepository = $messageRepository;
        $this->conversationRepository = $conversationRepository;
        $this->moderatorRepository = $moderatorRepository;
        $this->categoryRepository = $categoryRepository;
        $this->connectionRepository = $connectionRepository;
    }

    public function index(Request $request)
    {
        $conversationCount = $this->conversationRepository->getCount();
        $moderatorCount = $this->moderatorRepository->getCount();

        $moderator = $this->moderatorRepository->getById(Auth::guard('moderator')->user()->id);
        $moderators = $this->moderatorRepository->getAll();

        $position = collect($moderators)->filter(function ($m) use ($moderator) {
            return $m->id === $moderator->id;
        })->toArray();
        $moderatorConversationCount = (int)round($conversationCount / $moderatorCount);

        $position = array_keys($position)[0];
        $perPage = 20;

        $conversations = Conversation::offset($moderatorConversationCount * $position)->take($moderatorConversationCount)->get();
        $conversationsChunks = $conversations->chunk($perPage);
        $pagesCount = count($conversationsChunks);
        $page = $request->has('page') ? $request->get('page') : 0;
        if ($page >= $pagesCount) $page = 0;
        $conversations = $conversationsChunks[$page];

        return view('moderators.welcome', compact('conversations', 'pagesCount'));
    }

    public function conversation($id)
    {

        $conversation = $this->conversationRepository->getByEntityUrn($id);
        if (!$conversation) return redirect(route('moderators.conversations.index'));
        $messages = $this->messageRepository->getMessagesAllByConversationId($conversation->id);
        $categories = $this->categoryRepository->getParentsWithChild();
        $connection = $this->connectionRepository->getById($conversation->connection_id);

        return view('moderators.conversations', compact('messages','categories','conversation','connection'));

    }

    /**
     * @param int $id
     * @param ConnectionStatusRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function conversationStatus(int $id, ConnectionStatusRequest $request): \Illuminate\Http\RedirectResponse
    {
        $conversation = $this->conversationRepository->getById($id);

        $data = [
            "morphedModel" => Auth::guard('moderator')->user()->id,
            "morphClass" => class_basename(Moderator::class),
            "text" =>$request->get('text'),
            "categories" => $request->get('categories'),
        ];

        $this->connectionRepository->addStatus( $conversation->connection_id, $data);
        $this->putFlashMessage(true, 'successfully added');
        return redirect()->back();
    }
}
