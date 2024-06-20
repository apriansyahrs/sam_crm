<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'username' => 'admin',
            'is_active' => '1',
            'email' => 'admin@dev.com',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('password'),
        ])->syncRoles('Super Admin');

        User::create([
            'name' => 'User',
            'username' => 'user',
            'is_active' => '1',
            'email' => 'user@dev.com',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('password'),
        ])->syncRoles('User');
    }
}
