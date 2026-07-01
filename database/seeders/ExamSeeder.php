<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $now      = now();
        $classIds = DB::table('classes')->pluck('id')->all();

        $exams = [
            ['exam_name' => 'Monthly Test - June',  'days_offset' => -5,  'total_marks' => 50],
            ['exam_name' => 'Mid Term Exam',         'days_offset' => 10,  'total_marks' => 100],
            ['exam_name' => 'Final Term Exam',       'days_offset' => 60,  'total_marks' => 100],
            ['exam_name' => 'Monthly Test - May',    'days_offset' => -35, 'total_marks' => 50],
            ['exam_name' => '1st Unit Test',         'days_offset' => -60, 'total_marks' => 25],
        ];

        $count = 0;
        foreach ($exams as $exam) {
            foreach ($classIds as $classId) {
                DB::table('exams')->insert([
                    'exam_name'   => $exam['exam_name'],
                    'class_id'    => $classId,
                    'exam_date'   => now()->addDays($exam['days_offset'])->toDateString(),
                    'total_marks' => $exam['total_marks'],
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
                $count++;
            }
        }

        $this->command->info("✔ ExamSeeder: {$count} exam records inserted.");
    }
}
