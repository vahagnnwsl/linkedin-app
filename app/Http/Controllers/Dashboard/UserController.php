<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
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
    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, AccountRepository $accountRepository, KeyRepository $keyRepository)
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
        $roles = $this->roleRepository->getAll();

        return view('dashboard.users.create', compact('roles'));
    }


    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $user = $this->userRepository->getById($id);

        $roles = $this->roleRepository->getAll();

        $realAccounts = $this->accountRepository->getAllRealAccounts();

        $unRealAccounts = $this->accountRepository->getAllUnRealAccounts();

        $keys = $this->keyRepository->getAll();

        return view('dashboard.users.edit', compact('roles', 'user', 'realAccounts', 'keys', 'unRealAccounts'));
    }

    /**
     * @param UserRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UserRequest $request, $id): RedirectResponse
    {
        $data = $request->validated();

        $this->userRepository->update($id, Arr::except($data, ['role_id', 'account_id', 'keys_ides', 'unreal_accounts_ides']));

        $this->userRepository->syncRelation($id, 'roles', [$data['role_id']]);

        if (isset($data['keys_ides'])) {
            $this->userRepository->syncRelation($id, 'keys', $data['keys_ides']);
        }

        if (isset($data['account_id'])) {
            $this->userRepository->syncRelation($id, 'accounts', [$data['account_id']]);
        }

        if (isset($data['unreal_accounts_ides'])) {
            $this->userRepository->syncRelation($id, 'unRealAccounts', $data['unreal_accounts_ides']);
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

        $this->userRepository->syncRelation($user->id, 'roles', [$data['role_id']]);

        $user->notify(new SendPasswordNotification($request->get('password')));

        $this->putFlashMessage(true, 'successfully created');

        return redirect()->route('users.index');
    }


    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function updatePasswordForm(int $id)
    {
        $user = $this->userRepository->getById($id);

        return view('dashboard.users.password',compact('user'));
    }

    /**
     * @param int $id
     * @param PasswordRequest $request
     * @return RedirectResponse
     */
    public function updatePassword(int $id,PasswordRequest $request): RedirectResponse
    {

        $this->userRepository->update($id,['password'=>bcrypt($request->get('password'))]);

        $this->putFlashMessage(true, 'Successfully updated');

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


}
