<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class RoleController extends Controller
{

    protected $roleRepository;
    protected $permissionRepository;

    /**
     * RoleController constructor.
     * @param RoleRepository $roleRepository
     * @param PermissionRepository $permissionRepository
     */

    public function __construct(RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {

        $roles = $this->roleRepository->paginate();

        $permissions = $this->permissionRepository->getAll();

        return view('dashboard.roles.index', compact('roles', 'permissions'));

    }

    /**
     * @param $id
     * @return RoleResource
     */
    public function get($id)
    {
        $role = $this->roleRepository->getById($id);

        return new RoleResource($role);

    }

    /**
     * @param Request $request
     * @param $id
     * @return bool
     */
    public function syncPermissions(Request $request, $id): bool
    {

        $this->roleRepository->syncPermissions($id, $request->get('permissions'));

        return true;
    }


    /**
     * @param RoleRequest $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request): JsonResponse
    {

        $role = $this->roleRepository->store($request->validated());
        $this->putFlashMessage(true, 'successfully created');
        return response()->json(['data' => $role]);
    }

}
