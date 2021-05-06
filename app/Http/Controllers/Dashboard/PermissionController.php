<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\PermissionRepository;

class PermissionController extends Controller
{

    protected $permissionRepository;

    /**
     * PermissionController constructor.
     * @param PermissionRepository $permissionRepository
     */

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {

        $permissions = $this->permissionRepository->getAll();


        return view('dashboard.permissions.index', compact('permissions'));

    }

}
