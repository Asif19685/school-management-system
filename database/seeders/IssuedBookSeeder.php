<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IssuedBookSeeder extends Seeder
{
    public function run(): void
    {
        $now        = now();
        $bookIds    = DB::table('library_books')->pluck('id')->all();
        $studentIds = DB::table('students')->pluck('id')->all();
        $statuses   = ['issued', 'returned', 'overdue'];
        $count      = 0;

        if (empty($bookIds) || empty($studentIds)) {
            $this->command->warn('⚠ IssuedBookSeeder: No books or students found. Skipped.');
            return;
        }

        // Issue 15 books to random students
        for ($i = 0; $i < 15; $i++) {
            $issueDate  = now()->subDays(rand(1, 30));
            $status     = $statuses[array_rand($statuses)];
            $returnDate = $status === 'issued' ? now()->addDays(rand(3, 14))->toDateString() : now()->subDays(rand(1, 5))->toDateString();

            DB::table('issued_books')->insert([
                'book_id'     => $bookIds[array_rand($bookIds)],
                'student_id'  => $studentIds[array_rand($studentIds)],
                'issue_date'  => $issueDate->toDateString(),
                'return_date' => $returnDate,
                'status'      => $status,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
            $count++;
        }

        $this->command->info("✔ IssuedBookSeeder: {$count} issued book records inserted.");
    }
}
