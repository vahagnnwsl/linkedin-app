<?php

namespace App\Repositories;


class RoleRepository extends Repository
{

    public function model(): string
    {
        return \Spatie\Permission\Models\Role::class;
    }

    /**
     * @param int $id
     * @param array $permissions
     * @param int|false $user_id
     */
    public function syncPermissions(int $id, array $permissions, $user_id = false): void
    {
        $role = $this->getById($id);

        if ($role) {
            $role->syncPermissions($permissions);
        }

    }


}
