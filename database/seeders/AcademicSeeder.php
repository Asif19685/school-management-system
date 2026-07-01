<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $classIds = [];
        foreach (['Nursery', 'Class 1', 'Class 2'] as $className) {
            $classIds[] = DB::table('classes')->insertGetId([
                'class_name' => $className,
                'description' => $className . ' section',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach ($classIds as $classId) {
            foreach (['A', 'B'] as $sectionName) {
                DB::table('sections')->insert([
                    'class_id' => $classId,
                    'section_name' => $sectionName,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $courseIds = [];
        foreach ([['Mathematics', 'MATH-101'], ['English', 'ENG-101'], ['Computer Science', 'CS-101']] as [$name, $code]) {
            $courseIds[] = DB::table('courses')->insertGetId([
                'course_name' => $name,
                'course_code' => $code,
                'duration' => '12 Months',
                'fee' => 3500,
                'description' => "{$name} course",
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach ($courseIds as $idx => $courseId) {
            DB::table('subjects')->insert([
                'course_id' => $courseId,
                'subject_name' => 'Subject ' . ($idx + 1),
                'subject_code' => 'SUB-' . ($idx + 1),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
