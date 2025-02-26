<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class createAdminUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Super Admin DF',
                'email' => 'adminsuper@gmail.com',
                'password' => Hash::make('admin123'),
                'is_active' => true,
                'roles' => json_encode(['1'])
            ],
            [
                'name' => 'Admin DF',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'is_active' => true,
                'roles' => json_encode(['2'])
            ],
        ];
        DB::table('admins')->insert($admins);
    }
}
