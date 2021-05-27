<?php

namespace App\Http\Controllers\Api;

use App\Events\NewMessage;
use App\Linkedin\Responses\NewMessage as NewMessageResponse;
use App\Http\Controllers\Controller;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use App\Http\Resources\MessageResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;


class ConversationController extends Controller
{

    /**
     * @var ConversationRepository
     */
    protected $conversationRepository;

    /**
     * @var MessageRepository
     */
    protected $messageRepository;

    /**
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @var ConnectionRepository
     */
    protected $connectionRepository;

    /**
     * ConversationController constructor.
     * @param ConversationRepository $conversationRepository
     * @param MessageRepository $messageRepository
     * @param AccountRepository $accountRepository
     * @param ConnectionRepository $connectionRepository
     */
    public function __construct(ConversationRepository $conversationRepository, MessageRepository $messageRepository, AccountRepository $accountRepository, ConnectionRepository $connectionRepository)
    {
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
        $this->accountRepository = $accountRepository;
        $this->connectionRepository = $connectionRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $request = $request->all();

        $data = (new NewMessageResponse($request['payload']['included'], $request['login']))();

        $account = $this->accountRepository->getByLogin($data['login']);

        $conversationData = $data['conversation'];

        $conversationData['account_id'] = $account->id;

        $conversation = $this->conversationRepository->updateOrCreate(['entityUrn'=>$conversationData['entityUrn']], $conversationData);

        $writer = $data['writer'];

        $message = $data['message'];

        $message['event'] = $this->messageRepository::RECEIVE_EVENT;
        $message['status'] = $this->messageRepository::SENDED_STATUS;

        $message['conversation_id'] = $conversation->id;

        if ($writer['entityUrn'] !== $account->entityUrn) {

            $connection = $this->connectionRepository->updateOrCreate(['entityUrn' => $writer['entityUrn']], $writer);

            if (!$this->accountRepository->checkConnectionRelationExist($account->id, $connection->id)) {

                $this->accountRepository->attachConnections($account->id, [$connection->id]);
            }

            $this->conversationRepository->update($conversation->id,['connection_id'=>$connection->id]);

            $message['connection_id'] = $connection->id;

        } else {
            $message['account_id'] = $account->id;
        }

        $message = $this->messageRepository->updateOrCreate(['entityUrn'=>$message['entityUrn']],$message);


         if ($account->entityUrn  !==  $writer['entityUrn']) {
             event(new NewMessage((new MessageResource($message))->toArray([]), $account->entityUrn));

         }

        return response()->json(['data' => $message]);

    }
}
