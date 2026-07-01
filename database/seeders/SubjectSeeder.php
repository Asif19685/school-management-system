<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Subjects per course
        $subjectsMap = [
            'MATH-101' => ['Algebra', 'Geometry', 'Arithmetic'],
            'ENG-101'  => ['Grammar', 'Comprehension', 'Creative Writing'],
            'URD-101'  => ['Qawaid', 'Insha', 'Nasr'],
            'SCI-101'  => ['Physics', 'Chemistry', 'Biology'],
            'CS-101'   => ['MS Office', 'Programming Basics', 'Internet & Email'],
            'ISL-101'  => ['Quran', 'Islamic History', 'Ethics'],
            'SOC-101'  => ['Pakistan Studies', 'Geography', 'Civics'],
        ];

        $count = 0;
        foreach ($subjectsMap as $courseCode => $subjects) {
            $courseId = DB::table('courses')->where('course_code', $courseCode)->value('id');
            if (!$courseId) continue;

            foreach ($subjects as $idx => $subjectName) {
                DB::table('subjects')->insert([
                    'course_id'    => $courseId,
                    'subject_name' => $subjectName,
                    'subject_code' => strtoupper(substr($courseCode, 0, 3)) . '-S' . ($idx + 1),
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
                $count++;
            }
        }

        $this->command->info("✔ SubjectSeeder: {$count} subjects inserted.");
    }
}
