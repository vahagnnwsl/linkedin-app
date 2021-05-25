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

        Role::create([
            'name' => 'Admin',
            'icon' => 'fa fa-user-secret',
        ]);

        Role::create([
            'name' => 'Manager',
            'icon' => 'fa fa-users-cog',
        ]);

        Role::create([
            'name' => 'Hr',
            'icon' => 'fa fa-users-cog',
        ]);

    }
}


