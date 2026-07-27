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
            ['exam_name' => 'Monthly Test - June',  'days_offset' => -5,  'exam_type' => 'monthly_test'],
            ['exam_name' => 'Mid Term Exam',         'days_offset' => 10,  'exam_type' => 'mid_term'],
            ['exam_name' => 'Final Term Exam',       'days_offset' => 60,  'exam_type' => 'final_term'],
        ];

        $count = 0;
        foreach ($exams as $exam) {
            foreach ($classIds as $classId) {
                DB::table('exams')->insert([
                    'exam_name'   => $exam['exam_name'],
                    'exam_type'   => $exam['exam_type'],
                    'academic_year' => (int) date('Y'),
                    'class_id'    => $classId,
                    'exam_date'   => now()->addDays($exam['days_offset'])->toDateString(),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
                $count++;
            }
        }

        $this->command->info("✔ ExamSeeder: {$count} exam records inserted.");
    }
}
