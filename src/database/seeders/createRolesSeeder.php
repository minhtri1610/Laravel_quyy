<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class createRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'code' => '1',
                'slug' => 'super-admin',
                'permissions' => json_encode(['*'])
            ],
            [
                'name' => 'admin',
                'code' => '2',
                'slug' => 'admin',
                'permissions' => json_encode(['*'])
            ]
        ];

        DB::table('roles')->insert($roles);
    }
}
