<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;

class PermissionRepository
{


    /**
     * @return mixed
     */
    public function getAll()
    {
        return Permission::orderByDesc('created_at')->get();
    }

}
