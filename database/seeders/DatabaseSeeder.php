<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create a default user
        $userId = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a default department
        $deptId = DB::table('departments')->insertGetId([
            'name' => 'Computer Science',
            'code' => 'CSE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // We don't need to seed students here as the enroll endpoint will create them
    }
}
