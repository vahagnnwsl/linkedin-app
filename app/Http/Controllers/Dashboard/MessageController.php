<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\KeyRequest;
use App\Http\Resources\MessageResource;
use App\Jobs\LinkedinSearchByKey;
use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Repositories\ConversationRepository;
use App\Repositories\KeyRepository;
use App\Repositories\MessageRepository;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;


class MessageController extends Controller
{
    /**
     * @var MessageRepository
     */
    protected $messageRepository;

    /**
     * @var ConversationRepository
     */
    protected $conversationRepository;


    /**
     * MessageController constructor.
     * @param MessageRepository $messageRepository
     * @param ConversationRepository $conversationRepository
     */
    public function __construct(MessageRepository $messageRepository, ConversationRepository $conversationRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->conversationRepository = $conversationRepository;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function store(Request $request): JsonResponse
    {
        $conversation = $this->conversationRepository->getById($request->get('conversation_id'));

        $text = $request->get('text');

        $account = Auth::user()->account;

        $data = [
            'conversation_id' => $conversation->id,
            'text' => $text,
            'account_id' => $account->id,
            'user_id' => Auth::id(),
            'status' => $this->messageRepository::DRAFT_STATUS,
            'event' => $this->messageRepository::NOT_RECEIVE_EVENT,
            'date' => Carbon::now()->toDateTimeString()
        ];

        $response = Response::storeMessage(Api::conversation($account->login, $account->password)->writeMessage($text, $conversation->entityUrn));

        if ($response) {
            $data['status'] = $this->messageRepository::SENDED_STATUS;
            $data['entityUrn'] = $response['entityUrn'];
            $data['date'] = $response['date'];
        }

        $message = $this->messageRepository->store($data);

        return response()->json(['message' => new MessageResource($message)]);

    }

    /**
     * @throws GuzzleException
     */
    public function resend($id): JsonResponse
    {
        $account = Auth::user()->account;

        $message = $this->messageRepository->getById($id);

        $response = Response::storeMessage(Api::conversation($account->login, $account->password)->writeMessage($message->text, $message->conversation->entityUrn));

        if ($response) {

            $this->messageRepository->update($message->id, [
                'entityUrn' => $response['entityUrn'],
                'date' => $response['date'],
                'status' => $this->messageRepository::SENDED_STATUS
            ]);

            return response()->json([]);
        }

        return response()->json([], 411);

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function update($id): JsonResponse
    {

        $this->messageRepository->update($id, ['is_delete' => 1]);
        return response()->json([]);
    }


}
