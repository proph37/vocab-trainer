<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LanguageUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('language_user')->delete();

        DB::table('language_user')->insert([
            [
                'language_id' => 2,
                'user_id' => 1,
            ],
            [
                'language_id' => 3,
                'user_id' => 1,
            ]
        ]);
    }
}
