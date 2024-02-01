<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('languages')->delete();

        DB::table('languages')->insert([
            [
                'name' => 'Bosnian',
            ],
            [
                'name' => 'Croatian',
            ],
            [
                'name' => 'English',
            ],
            [
                'name' => 'German',
            ],
            [
                'name' => 'French',
            ],
            [
                'name' => 'Serbian',
            ],
        ]);
    }
}
