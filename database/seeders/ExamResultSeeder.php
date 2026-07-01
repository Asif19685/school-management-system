<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamResultSeeder extends Seeder
{
    public function run(): void
    {
        $now        = now();
        $studentIds = DB::table('students')->pluck('id')->all();
        $subjectIds = DB::table('subjects')->pluck('id')->all();
        $examIds    = DB::table('exams')->pluck('id')->all();

        if (empty($examIds) || empty($studentIds) || empty($subjectIds)) {
            $this->command->warn('⚠ ExamResultSeeder: Missing exams, students, or subjects. Skipped.');
            return;
        }

        $grades = ['A+', 'A', 'B+', 'B', 'C+', 'C', 'D'];
        $count  = 0;

        // For first 2 exams, add results for all students across first 3 subjects
        foreach (array_slice($examIds, 0, 2) as $examId) {
            foreach ($studentIds as $studentId) {
                $subjectId    = $subjectIds[array_rand($subjectIds)];
                $obtainedMarks = rand(30, 95);
                $grade        = $obtainedMarks >= 90 ? 'A+' : ($obtainedMarks >= 80 ? 'A' : ($obtainedMarks >= 70 ? 'B+' : ($obtainedMarks >= 60 ? 'B' : ($obtainedMarks >= 50 ? 'C' : 'D'))));

                DB::table('exam_results')->insert([
                    'exam_id'        => $examId,
                    'student_id'     => $studentId,
                    'subject_id'     => $subjectId,
                    'obtained_marks' => $obtainedMarks,
                    'grade'          => $grade,
                    'remarks'        => $obtainedMarks >= 60 ? 'Pass' : 'Fail',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]);
                $count++;
            }
        }

        $this->command->info("✔ ExamResultSeeder: {$count} exam results inserted.");
    }
}
