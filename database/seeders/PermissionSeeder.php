<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Permission::create([
            'name' => 'permissions'
        ]);

        Permission::create([
            'name' => 'roles'
        ]);

        Permission::create([
            'name' => 'users'
        ]);

        Permission::create([
            'name' => 'accounts'
        ]);

        Permission::create([
            'name' => 'connections'
        ]);

        Permission::create([
            'name' => 'conversations'
        ]);

        Permission::create([
            'name' => 'keys'
        ]);

    }
}


