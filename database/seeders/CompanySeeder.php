<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Company 1',
                'email' => 'company1@email.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Company 2',
                'email' => 'company2@email.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Company 3',
                'email' => 'company3@email.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Company 4',
                'email' => 'company4@email.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Company 5',
                'email' => 'company5@email.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('companies')->insert($companies);
    }
}
