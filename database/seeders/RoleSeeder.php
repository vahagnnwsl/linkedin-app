<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


        $adminRole = Role::create([
            'name' => 'Admin',
            'icon' => 'fa fa-user-secret',
        ]);

        $permissions = Permission::pluck('id')->toArray();

        $adminRole->syncPermissions($permissions);


        Role::create([
            'name' => 'Hr',
            'icon' => 'fa fa-users-cog',
        ]);

        User::first()->roles()->sync($adminRole->id);

    }
}


