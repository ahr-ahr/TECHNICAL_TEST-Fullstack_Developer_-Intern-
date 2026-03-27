<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Approver Level 1',
            'email' => 'approver1@test.com',
            'password' => bcrypt('123456'),
            'role' => 'approver',
        ]);

        User::create([
            'name' => 'Approver Level 2',
            'email' => 'approver2@test.com',
            'password' => bcrypt('123456'),
            'role' => 'approver',
        ]);
    }
}
