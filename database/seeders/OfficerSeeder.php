<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OfficerSeeder extends Seeder
{
    public function run(): void
    {
        $officers = [
            [
                'name' => 'Officer One',
                'email' => 'officer1@example.com',
                'tel_number' => '0123456789',
                'password' => Hash::make('officer123'), // Password: officer123
                'role' => 'officer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Officer Two',
                'email' => 'officer2@example.com',
                'tel_number' => '0987654321',
                'password' => Hash::make('officer123'),
                'role' => 'officer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($officers);
    }
}
