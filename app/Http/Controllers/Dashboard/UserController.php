<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Notifications\SendPasswordNotification;
use App\Repositories\AccountRepository;
use App\Repositories\KeyRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{


    protected $userRepository;

    protected $accountRepository;

    protected $roleRepository;

    protected $keyRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     * @param RoleRepository $roleRepository
     * @param AccountRepository $accountRepository
     * @param KeyRepository $keyRepository
     */
    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, AccountRepository $accountRepository,KeyRepository $keyRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->accountRepository = $accountRepository;
        $this->keyRepository = $keyRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {

        $users = $this->userRepository->paginate();

        return view('dashboard.users.index', compact('users'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('dashboard.users.create');
    }


    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $user = $this->userRepository->getById($id);
        if (!$user) {
            abort(404);
        }
        $roles = $this->roleRepository->getAll();
        $accounts = $this->accountRepository->getAll();
        $keys = $this->keyRepository->getAll();


        return view('dashboard.users.edit', compact('roles', 'user', 'accounts','keys'));

    }

    /**
     * @param UserRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UserRequest $request, $id): RedirectResponse
    {
        $data = $request->validated();

        $this->userRepository->update($id, Arr::except($data, ['role_id', 'account_id','keys_ides']));

        $this->userRepository->syncRole($id, $data['role_id']);

        if (isset($data['keys_ides'])) {
            $this->userRepository->syncKeys($id, $data['keys_ides']);
        }

        if (isset($data['account_id'])){
            $this->userRepository->syncAccounts($id, $data['account_id']);
        }

        $this->putFlashMessage(true, 'Successfully updated');
        return redirect()->route('users.edit', $id);

    }

    /**
     * @param UserRequest $request
     * @return RedirectResponse
     */
    public function store(UserRequest $request): RedirectResponse
    {

        $data = $request->validated();

        $data['status'] = UserRepository::ACTIVE_STATUS;
        $data['password'] = bcrypt($data['password']);

        $user = $this->userRepository->store($data);

        $user->notify(new SendPasswordNotification($request->get('password')));

        $this->putFlashMessage(true, 'successfully created');

        return redirect()->route('users.index');
    }


    /**
     * @param $id
     * @return RedirectResponse
     */
    public function login($id): RedirectResponse
    {
        Auth::loginUsingId($id, true);
        $this->putFlashMessage(true, 'successfully');

        return redirect('/dashboard');
    }

    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function linkedin(int $id)
    {

        $user = $this->userRepository->getById($id);
        if (!$user) {
            abort(404);
        }

        $conversations = $user->linkedinConversations()->orderByDesc('lastActivityAt')->get();

        return view('dashboard.users.linkedin', compact('user', 'conversations'));
    }

}
