<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $teachers = [
            ['name' => 'Muhammad Tariq',  'email' => 'tariq@sms.local',   'phone' => '03011112201', 'qualification' => 'M.Sc Mathematics',   'salary' => 45000],
            ['name' => 'Ayesha Noman',    'email' => 'ayesha@sms.local',  'phone' => '03011112202', 'qualification' => 'MA English',          'salary' => 42000],
            ['name' => 'Irfan Hussain',   'email' => 'irfan@sms.local',   'phone' => '03011112203', 'qualification' => 'B.Sc Physics',        'salary' => 40000],
            ['name' => 'Sana Rehman',     'email' => 'sana@sms.local',    'phone' => '03011112204', 'qualification' => 'M.A Urdu',            'salary' => 38000],
            ['name' => 'Zubair Qureshi',  'email' => 'zubair@sms.local',  'phone' => '03011112205', 'qualification' => 'MCS',                 'salary' => 50000],
            ['name' => 'Hina Bashir',     'email' => 'hina@sms.local',    'phone' => '03011112206', 'qualification' => 'M.A Islamic Studies', 'salary' => 35000],
            ['name' => 'Kamran Aziz',     'email' => 'kamran@sms.local',  'phone' => '03011112207', 'qualification' => 'M.Sc Chemistry',      'salary' => 43000],
            ['name' => 'Rabia Farooq',    'email' => 'rabia@sms.local',   'phone' => '03011112208', 'qualification' => 'B.Ed',                'salary' => 37000],
        ];

        foreach ($teachers as $idx => $teacher) {
            DB::table('teachers')->insert([
                'user_id'       => null,
                'teacher_code'  => 'TCH-' . str_pad((string)($idx + 1), 3, '0', STR_PAD_LEFT),
                'name'          => $teacher['name'],
                'email'         => $teacher['email'],
                'phone'         => $teacher['phone'],
                'qualification' => $teacher['qualification'],
                'joining_date'  => now()->subMonths(rand(3, 36))->toDateString(),
                'salary'        => $teacher['salary'],
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        $this->command->info('✔ TeacherSeeder: ' . count($teachers) . ' teachers inserted.');
    }
}
