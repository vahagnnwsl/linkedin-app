<?php

namespace Database\Seeders;

use App\Http\Repositories\RoleRepository;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


        $admin = User::create([
            'first_name' => 'Vladimir',
            'last_name' => 'Ghukasyan',
            'email' => 'vladimir.ghukas@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'status' => 1
        ]);

        $admin->assignRole('Admin');

    }
}

