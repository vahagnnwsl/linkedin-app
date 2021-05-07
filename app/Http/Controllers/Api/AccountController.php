<?php

namespace App\Http\Controllers\Api;

use App\Events\NewMessage;
use App\Events\SyncConversations;
use App\Http\Controllers\Controller;
use App\Models\ConnectionRequest;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use App\Http\Resources\MessageResource;
use App\Linkedin\Responses\Response;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Linkedin\Api;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class AccountController extends Controller
{

    /**
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * ConversationController constructor.
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function update(int $id): JsonResponse
    {
        $this->accountRepository->update($id, [
            'lastActivityAt' => Carbon::now()->toDateTimeString()
        ]);

        return response()->json([]);
    }
}
