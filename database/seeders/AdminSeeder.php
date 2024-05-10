<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@shaz3e.com',
                'password' => Hash::make('123456789'),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tester',
                'email' => 'tester@shaz3e.com',
                'password' => Hash::make('123456789'),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Developer',
                'email' => 'developer@shaz3e.com',
                'password' => Hash::make('123456789'),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@shaz3e.com',
                'password' => Hash::make('123456789'),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'manager',
                'email' => 'manager@shaz3e.com',
                'password' => Hash::make('123456789'),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'staff',
                'email' => 'staff@shaz3e.com',
                'password' => Hash::make('123456789'),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('admins')->insert($admins);
    }
}
