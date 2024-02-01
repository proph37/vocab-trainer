<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();

        DB::table('users')->insert([
            [
                'first_name' => 'Maris',
                'last_name' => 'Siljak',
                'email' => 'maris.siljak@outlook.com',
                'password' => Hash::make('quelle12ba'),
                'remember_token' => Str::random(10),
                'role_id' => Role::firstWhere('name', 'Admin')->id,
                'native_language_id' => Language::firstWhere('name', 'Bosnian')->id,
                'points' => 0,
                'streak' => 0,
                'multiplier' => 1,
            ],
            [
                'first_name' => 'Ibela',
                'last_name' => 'Siljak',
                'email' => 'bela.siljak@gmail.com',
                'password' => Hash::make('ibela123'),
                'remember_token' => Str::random(10),
                'role_id' => Role::firstWhere('name', 'Student')->id,
                'native_language_id' => Language::firstWhere('name', 'Bosnian')->id,
                'points' => 0,
                'streak' => 0,
                'multiplier' => 1,
            ]
        ]);
    }
}
