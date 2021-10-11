<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Repositories\AccountRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


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
    public function update(int $id, Request $request): JsonResponse
    {
        $is_online = $request->get('is_online') ?? 0;

        Log::alert('Id_'.$id,['req'=>$request->all()]);
        $this->accountRepository->update($id, [
            'lastActivityAt' => Carbon::now()->toDateTimeString(),
            'is_online' => $is_online
        ]);

        return response()->json(['is_online' => $is_online]);
    }
}
