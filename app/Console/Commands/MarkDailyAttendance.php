<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\StudentAdmission;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkDailyAttendance extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'attendance:mark-daily
                            {--date= : Custom date to mark attendance for (format: Y-m-d). Defaults to today.}';

    /**
     * The console command description.
     */
    protected $description = 'Automatically mark all approved students as Present for today (skips already marked).';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $date = $this->option('date')
            ? Carbon::createFromFormat('Y-m-d', $this->option('date'))->toDateString()
            : today()->toDateString();

        $this->info("Marking daily attendance for date: {$date}");

        // Get all approved student admissions
        $admissions = StudentAdmission::with('student')
            ->where('status', 'approved')
            ->get();

        $marked  = 0;
        $skipped = 0;

        foreach ($admissions as $admission) {
            $student = $admission->student;
            if (!$student) {
                continue;
            }

            // Check if already marked for this date
            $exists = Attendance::where('student_id', $student->id)
                ->whereDate('attendance_date', $date)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            // Mark as Present (default — teacher can change absent ones later)
            Attendance::create([
                'student_id'      => $student->id,
                'attendance_date' => $date,
                'status'          => 'present',
            ]);

            $marked++;
        }

        $this->info("✅ Marked: {$marked} students as Present.");
        $this->info("⏭️  Skipped: {$skipped} (already had a record for {$date}).");

        return Command::SUCCESS;
    }
}
