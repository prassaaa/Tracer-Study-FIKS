<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@tracerstudy.com',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
            'status' => 'approved',
        ]);
    }
}
