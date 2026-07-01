<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CoreUserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('users')->insert([
            'name' => 'SMS Admin',
            'email' => 'admin@sms.local',
            'password' => Hash::make('password'),
            'email_verified_at' => $now,
            'remember_token' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
