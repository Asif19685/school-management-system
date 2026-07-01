<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $now        = now();
        $studentIds = DB::table('students')->pluck('id')->all();
        $statuses   = ['present', 'present', 'present', 'present', 'absent', 'leave'];
        $count      = 0;

        // Last 7 days attendance, skip weekends
        for ($day = 6; $day >= 0; $day--) {
            $date = now()->subDays($day)->toDateString();
            $dayOfWeek = now()->subDays($day)->dayOfWeek;

            // Skip Friday (5) and Saturday (6)
            if (in_array($dayOfWeek, [5, 6])) continue;

            foreach ($studentIds as $studentId) {
                // Check for duplicate (unique constraint on student_id + attendance_date)
                $exists = DB::table('attendance')
                    ->where('student_id', $studentId)
                    ->where('attendance_date', $date)
                    ->exists();

                if (!$exists) {
                    DB::table('attendance')->insert([
                        'student_id'      => $studentId,
                        'attendance_date' => $date,
                        'status'          => $statuses[array_rand($statuses)],
                        'remarks'         => null,
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ]);
                    $count++;
                }
            }
        }

        $this->command->info("✔ AttendanceSeeder: {$count} attendance records inserted.");
    }
}
