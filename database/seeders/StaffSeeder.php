<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminUserId = DB::table('users')->where('email', 'admin@sms.local')->value('id');

        DB::table('teachers')->insert([
            [
                'user_id' => $adminUserId,
                'teacher_code' => 'TCH-001',
                'name' => 'Ali Teacher',
                'email' => 'teacher1@sms.local',
                'phone' => '03211234567',
                'qualification' => 'MSc',
                'joining_date' => now()->subYear()->toDateString(),
                'salary' => 65000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => null,
                'teacher_code' => 'TCH-002',
                'name' => 'Sara Teacher',
                'email' => 'teacher2@sms.local',
                'phone' => '03221234567',
                'qualification' => 'MA',
                'joining_date' => now()->subMonths(8)->toDateString(),
                'salary' => 60000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
