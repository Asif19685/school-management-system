<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $studentIds = DB::table('students')->pluck('id')->all();
        $subjectIds = DB::table('subjects')->pluck('id')->all();
        $classId = DB::table('classes')->value('id');
        $adminUserId = DB::table('users')->where('email', 'admin@sms.local')->value('id');

        foreach (array_slice($studentIds, 0, 10) as $studentId) {
            DB::table('attendance')->insert([
                'student_id' => $studentId,
                'attendance_date' => now()->toDateString(),
                'status' => rand(0, 10) > 1 ? 'present' : 'absent',
                'remarks' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $examId = DB::table('exams')->insertGetId([
            'exam_name' => 'Mid Term',
            'class_id' => $classId,
            'exam_date' => now()->addDays(10)->toDateString(),
            'total_marks' => 100,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach (array_slice($studentIds, 0, 6) as $studentId) {
            DB::table('exam_results')->insert([
                'exam_id' => $examId,
                'student_id' => $studentId,
                'subject_id' => $subjectIds[array_rand($subjectIds)],
                'obtained_marks' => rand(55, 95),
                'grade' => 'A',
                'remarks' => 'Good',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $feeIds = [];
        foreach ($studentIds as $studentId) {
            $feeIds[] = DB::table('fees')->insertGetId([
                'student_id' => $studentId,
                'fee_type' => 'Monthly',
                'amount' => rand(2500, 5000),
                'fine_amount' => rand(0, 300),
                'discount_amount' => rand(0, 200),
                'due_date' => now()->addDays(rand(2, 25))->toDateString(),
                'status' => rand(0, 10) > 4 ? 'paid' : 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach (array_slice($feeIds, 0, 7) as $feeId) {
            DB::table('fee_payments')->insert([
                'fee_id' => $feeId,
                'paid_amount' => rand(2000, 5000),
                'payment_date' => now()->subDays(rand(0, 20))->toDateString(),
                'payment_method' => 'Cash',
                'receipt_no' => 'RCPT-' . rand(1000, 9999),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach (range(1, 6) as $i) {
            DB::table('visitors')->insert([
                'name' => "Visitor {$i}",
                'phone' => '034512345' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'purpose' => 'Student Enquiry',
                'meeting_with' => 'Reception',
                'time_in' => now()->subHours(rand(1, 6)),
                'time_out' => now()->subHours(rand(0, 1)),
                'visitor_type' => 'Parent',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::table('notifications')->insert([
            [
                'user_id' => $adminUserId,
                'title' => 'Fee Reminder',
                'message' => 'Monthly fee due this week.',
                'channel' => 'system',
                'is_read' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $adminUserId,
                'title' => 'Attendance Alert',
                'message' => 'Low attendance in Class 2.',
                'channel' => 'system',
                'is_read' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $bookId = DB::table('library_books')->insertGetId([
            'title' => 'Science Fundamentals',
            'author' => 'John Doe',
            'isbn' => '978-1-11111-111-1',
            'quantity' => 15,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('issued_books')->insert([
            'book_id' => $bookId,
            'student_id' => $studentIds[0] ?? null,
            'issue_date' => now()->subDays(5)->toDateString(),
            'return_date' => now()->addDays(5)->toDateString(),
            'status' => 'issued',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
