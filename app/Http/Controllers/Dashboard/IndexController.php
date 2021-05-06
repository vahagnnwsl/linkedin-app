<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\ConversationCollection;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * IndexController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function home()
    {
        return view('dashboard.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function profile()
    {
        return view('dashboard.account.profile');
    }

    /**
     * @param ProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(ProfileRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('avatar')) {

            $path = $request->file('avatar')->store('/public/avatars/' . Auth::id());
            $data['avatar'] = explode('public/', $path)[1];
        }

        $this->userRepository->update($data, Auth::id());

        $this->putFlashMessage(true);

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     */
    public function linkedinChat(Request $request)
    {
        $user = Auth::user();

        $conversations = new ConversationCollection($user->conversations()->orderByDesc('lastActivityAt')->get());

        if ($request->ajax()) {
            return response()->json(['threads' => $conversations]);
        }

        return view('dashboard.account.linkedin-chat', compact('user', 'conversations'));
    }

    /**
     * @return Application|Factory|View
     */
    public function linkedinSearch()
    {
        $user = Auth::user();
        return view('dashboard.account.linkedin-search', compact('user'));
    }
}
