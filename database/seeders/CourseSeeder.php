<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $courses = [
            ['course_name' => 'Mathematics',      'course_code' => 'MATH-101', 'duration' => '12 Months', 'fee' => 3500, 'description' => 'Basic to advanced mathematics'],
            ['course_name' => 'English Language',  'course_code' => 'ENG-101',  'duration' => '12 Months', 'fee' => 3000, 'description' => 'English grammar and literature'],
            ['course_name' => 'Urdu',              'course_code' => 'URD-101',  'duration' => '12 Months', 'fee' => 2500, 'description' => 'Urdu language and literature'],
            ['course_name' => 'Science',           'course_code' => 'SCI-101',  'duration' => '12 Months', 'fee' => 3500, 'description' => 'General science concepts'],
            ['course_name' => 'Computer Science',  'course_code' => 'CS-101',   'duration' => '12 Months', 'fee' => 4000, 'description' => 'Computer fundamentals and programming'],
            ['course_name' => 'Islamic Studies',   'course_code' => 'ISL-101',  'duration' => '12 Months', 'fee' => 2000, 'description' => 'Islamic education'],
            ['course_name' => 'Social Studies',    'course_code' => 'SOC-101',  'duration' => '12 Months', 'fee' => 2500, 'description' => 'History and geography'],
        ];

        foreach ($courses as $course) {
            DB::table('courses')->insert([
                'course_name' => $course['course_name'],
                'course_code' => $course['course_code'],
                'duration'    => $course['duration'],
                'fee'         => $course['fee'],
                'description' => $course['description'],
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }

        $this->command->info('✔ CourseSeeder: ' . count($courses) . ' courses inserted.');
    }
}
