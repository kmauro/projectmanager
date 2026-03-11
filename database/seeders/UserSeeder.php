<?php

namespace Database\Seeders;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


use function Symfony\Component\Clock\now;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'user' => 'admin',
                'password' => Hash::make('adminadmin'),
                'name' => 'admin',
                'email' => 'test@test.com',
                'email_verified_at' => now(),
                'phone' => '1122334455',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('users')->insert(
            $users
        );
    }
}
