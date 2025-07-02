<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'role_admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kasir User',
                'email' => 'kasir@example.com',
                'password' => Hash::make('password'),
                'role' => 'role_kasir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => ' Dapur User',
                'email' => 'dapur@example.com',
                'password' => Hash::make('password'),
                'role' => 'role_dapur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}