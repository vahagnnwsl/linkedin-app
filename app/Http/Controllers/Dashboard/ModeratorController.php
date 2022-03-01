<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModeratorRequest;
use App\Repositories\ModeratorRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class ModeratorController extends Controller
{

    protected $moderatorRepository;


    /**
     * @param ModeratorRepository $moderatorRepository
     */
    public function __construct(ModeratorRepository $moderatorRepository)
    {
        $this->moderatorRepository = $moderatorRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {

        $moderators = $this->moderatorRepository->paginate();
        return view('dashboard.moderators.index', compact('moderators'));

    }


    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $moderator = $this->moderatorRepository->getById($id);

        return view('dashboard.moderators.edit', compact('moderator'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('dashboard.moderators.create');
    }


    /**
     * @param ModeratorRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ModeratorRequest $request): \Illuminate\Http\RedirectResponse
    {

        $data = $request->validated();
        $data['password_non_hash'] = $data['password'];
        $data['password'] = Hash::make($data['password']);

        $this->moderatorRepository->store($data);
        $this->putFlashMessage(true, 'successfully created');
        return redirect()->route('moderators.index');
    }

    /**
     * @param ModeratorRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ModeratorRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $data['password_non_hash'] = $data['password'];
        $data['password'] = Hash::make($data['password']);
        $this->moderatorRepository->update($id, $data);
        $this->putFlashMessage(true, 'successfully updated');
        return redirect()->route('moderators.index');
    }


    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id): \Illuminate\Http\RedirectResponse
    {
        $this->moderatorRepository->delete($id);
        $this->putFlashMessage(true, 'successfully deleted');
        return redirect()->back();
    }
}
