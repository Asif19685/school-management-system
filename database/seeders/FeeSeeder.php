<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeeSeeder extends Seeder
{
    public function run(): void
    {
        $now        = now();
        $studentIds = DB::table('students')->pluck('id')->all();
        $feeTypes   = ['Monthly', 'Admission', 'Annual', 'Exam'];
        $statuses   = ['pending', 'paid', 'paid', 'paid', 'partial'];
        $count      = 0;

        foreach ($studentIds as $studentId) {
            // Monthly fee for last 3 months
            for ($month = 0; $month < 3; $month++) {
                DB::table('fees')->insert([
                    'student_id'      => $studentId,
                    'fee_type'        => 'Monthly',
                    'amount'          => rand(2500, 5000),
                    'fine_amount'     => rand(0, 300),
                    'discount_amount' => rand(0, 200),
                    'due_date'        => now()->subMonths($month)->startOfMonth()->toDateString(),
                    'status'          => $statuses[array_rand($statuses)],
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);
                $count++;
            }
        }

        $this->command->info("✔ FeeSeeder: {$count} fee records inserted.");
    }
}
