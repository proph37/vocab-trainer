<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->delete();

        DB::table('roles')->insert([
            [
                'name' => 'Admin',
                'access_level' => 1,
            ],
            [
                'name' => 'Student',
                'access_level' => 4,
            ]
        ]);
    }
}
