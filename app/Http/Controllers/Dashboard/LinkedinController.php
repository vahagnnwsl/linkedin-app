<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\ConversationCollection;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;
use App\Http\Resources\Collections\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Linkedin\Responses\Response;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Linkedin\Api;

class LinkedinController extends Controller
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
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * LinkedinController constructor.
     * @param MessageRepository $messageRepository
     * @param ConversationRepository $conversationRepository
     * @param UserRepository $userRepository
     */

    public function __construct(MessageRepository $messageRepository, ConversationRepository $conversationRepository, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;
        $this->conversationRepository = $conversationRepository;
    }


    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     */
    public function chat(Request $request)
    {
        $conversations = [];

        if ($account = Auth::user()->account) {

            $conversations = new ConversationCollection($account->conversations()->orderByDesc('lastActivityAt')->get());

            if ($request->ajax()) {
                return response()->json(['conversations' => $conversations]);
            }
        }

        return view('dashboard.linkedin.chat', compact('account', 'conversations'));
    }


    /**
     * @return Application|Factory|View
     */
    public function search()
    {
        $account = Auth::user()->account;

        return view('dashboard.linkedin.search', compact('account'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function searchConnection(Request $request): JsonResponse
    {
        $account = Auth::user()->account;
        $result = Response::profiles((array)Api::profile($account->login, $account->password)->searchPeople($request->get('key')));
        return response()->json(['profiles' => $result]);
    }

    /**
     * @return Application|Factory|View
     */
    public function sendInvitations()
    {
        $account = Auth::user()->account;
        return view('dashboard.linkedin.send-invitations', compact('account'));
    }

    /**
     * @return JsonResponse
     */
    public function getSentInvitations(): JsonResponse
    {
        $account = Auth::user()->account;
        return response()->json(['invitations' => Response::invitations((array)Api::invitation($account->login, $account->password)->getSentInvitations())]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function storeMessage(Request $request): JsonResponse
    {

        $conversation = $this->conversationRepository->getById($request->get('conversation_id'));

        if ($conversation) {

            try {
                $resp = Response::storeMessage(Api::conversation(Auth::user()->linkedin_login, Auth::user()->linkedin_password)->writeMessage($request->get('text'), $conversation->entityUrn));

                $data = [
                    'conversation_id' => $conversation->id,
                    'conversation_entityUrn' => $conversation->entityUrn,
                    'user_entityUrn' => Auth::user()->linkedin_entityUrn,
                    'text' => $request->get('text'),
                    'status' => $this->messageRepository::DRAFT_STATUS,
                    'event' => $this->messageRepository::NOT_RECEIVE_EVENT,
                    'date' => Carbon::now()->toDateTimeString()
                ];

                if ($resp) {
                    $data['status'] = $this->messageRepository::SENDED_STATUS;
                    $data['entityUrn'] = $resp['entityUrn'];
                    $data['date'] = $resp['date'];
                }

                $message = $this->messageRepository->store($data);

                return response()->json(['message' => new MessageResource($message)]);
            } catch (\Exception $exception) {
                return response()->json(['error' => $exception->getMessage()], $exception->getCode());
            }
        }

        return response()->json(['error' => 'Conversation not founded'], 411);
    }

    /**
     * @param int $conversation_id
     * @param int $message_id
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function resendMessage(int $conversation_id, int $message_id): JsonResponse
    {
        $message = $this->messageRepository->getById($message_id);

        $conversation = $this->conversationRepository->getById($conversation_id);

        if ($message && $conversation) {

            if ($message->entityUrn && $message->status && $message->event) {
                return response()->json(['message' => new MessageResource($message)]);
            }

            try {
                $resp = Response::storeMessage(Api::conversation(Auth::user()->linkedin_login, Auth::user()->linkedin_password)->writeMessage($message->text, $conversation->entityUrn));

                if ($resp) {

                    $this->messageRepository->update($resp, $message->id);

                    return response()->json([]);
                }
                return response()->json(['error' => 'Api error']);

            } catch (\Exception $exception) {
                return response()->json(['error' => $exception->getMessage()], $exception->getCode());
            }
        }

        return response()->json([]);
    }

    /**
     * @param int $conversation_id
     * @param int $user_id
     * @return JsonResponse
     */
    public function getConversationMessages(int $conversation_id, int $user_id): JsonResponse
    {
        $user = $this->userRepository->getById($user_id);

        if ($user) {
            return response()->json(['messages' => new MessageCollection($this->messageRepository->getConversationMessagesForUser($conversation_id, $user->linkedin_entityUrn)->keyBy->entityUrn)]);
        }

        return response()->json(['error' => 'User not founded'], 411);
    }

    /**
     * @param int $conversation_id
     * @return JsonResponse
     */
    public function syncConversationMessages(int $conversation_id): JsonResponse
    {
        $conversation = $this->conversationRepository->getById($conversation_id);

        try {

            $response = Response::messages((array)Api::conversation(Auth::user()->linkedin_login, Auth::user()->linkedin_password)->getConversationMessages($conversation->entityUrn), $conversation->entityUrn);
            $this->messageRepository->updateOrCreateCollection($response, $conversation_id, $conversation->entityUrn, $this->messageRepository::SENDED_STATUS, $this->messageRepository::RECEIVE_EVENT);
            return response()->json([]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * @return JsonResponse
     */
    public function syncConversations(): JsonResponse
    {

        try {
            Artisan::call('command:LinkedinSyncConversationsForOneUser ' . Auth::id());
            return response()->json([]);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendInvitation(Request $request): JsonResponse
    {
        return response()->json(Api::invitation(Auth::user()->linkedin_login, Auth::user()->linkedin_password)->sendInvitation($request->get('profile_id'), $request->get('tracking_id'), $request->get('message')));
    }



    /**
     * @return JsonResponse
     */
    public function getReceivedInvitations(): JsonResponse
    {
        return response()->json(['invitations' => Response::invitations((array)Api::invitation(Auth::user()->linkedin_login, Auth::user()->linkedin_password)->getReceivedInvitations(), true)]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function replyInvitation(Request $request, string $id): JsonResponse
    {
        $resp = Api::invitation(Auth::user()->linkedin_login, Auth::user()->linkedin_password)->replyInvitation($id, $request->get('sharedSecret'), $request->get('action'));

        return response()->json(['invitations' => $resp], $resp['status']);
    }

}
