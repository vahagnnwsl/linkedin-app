<?php

namespace App\Http\Controllers\Moderators;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConnectionStatusRequest;
use App\Models\Conversation;
use App\Models\Moderator;
use App\Repositories\CategoryRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use App\Repositories\ModeratorRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
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

    /**
     * @param MessageRepository $messageRepository
     * @param ConversationRepository $conversationRepository
     * @param ModeratorRepository $moderatorRepository
     * @param CategoryRepository $categoryRepository
     * @param ConnectionRepository $connectionRepository
     */
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

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
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

        $conversations = Conversation::with('connection','connection.statuses')
            ->offset($moderatorConversationCount * $position)
            ->take($moderatorConversationCount)
            ->get();

        $conversations = $conversations->take(100);

        $status = $request->get('status') ?? 2;


        if ((int)$status === 1 || (int)$status === 0) {

            $conversations = $conversations->filter(function ($conversation) use($status) {

                if ((int)$status === 0){
                    return !count($conversation->connection->statuses);
                }
                if ((int)$status === 1){
                    return (bool)count($conversation->connection->statuses);
                }
            });
        }
        $page = $request->has('page') ? $request->get('page') : 0;

       if ($conversations->count()){
           $conversationsChunks = $conversations->chunk($perPage);
           $pagesCount = count($conversationsChunks);
           $conversations = $conversationsChunks[$page];

       }else{
           $pagesCount = 0;
       }
        if ($page >= $pagesCount) $page = 0;

        return view('moderators.welcome', compact('conversations', 'pagesCount'));
    }

    /**
     * @param string $id
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function conversation(string $id)
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
     * @return RedirectResponse
     */
    public function conversationStatus(int $id, ConnectionStatusRequest $request): RedirectResponse
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
