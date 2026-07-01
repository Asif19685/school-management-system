<?php

namespace App\Console\Commands;

use App\Models\Fee;
use App\Models\StudentAdmission;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMonthlyFees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fees:generate-monthly {--month= : Custom month to generate fees for (format: Y-m)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generate monthly fee records for all approved students';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting monthly fee generation...');

        // Determine target month and year
        $targetDate = $this->option('month') 
            ? Carbon::createFromFormat('Y-m', $this->option('month'))->startOfMonth()
            : Carbon::now()->addMonth()->startOfMonth(); // Default to next month

        $monthName = $targetDate->format('F Y');
        $feeType = 'Monthly Fee - ' . $monthName;
        $dueDate = $targetDate->copy()->day(10); // Due date is 10th of the month

        $this->info("Target month: {$monthName}");
        $this->info("Due date: {$dueDate->format('Y-m-d')}");

        // Get all approved student admissions
        $admissions = StudentAdmission::with(['student'])
            ->where('status', 'approved')
            ->get();

        $count = 0;
        $skipped = 0;

        foreach ($admissions as $admission) {
            $student = $admission->student;
            if (!$student) {
                continue;
            }

            // Check if fee already exists for this student and month
            $exists = Fee::where('student_id', $student->id)
                ->where('fee_type', $feeType)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            // Retrieve latest fee record of this student to clone their fee amount & discount structure
            $latestFee = Fee::where('student_id', $student->id)
                ->latest()
                ->first();

            // Default base amount if no previous fee record exists
            $amount = $latestFee ? parseFloatOrZero($latestFee->amount) : 3000.00;
            $discount = $latestFee ? parseFloatOrZero($latestFee->discount_amount) : 0.00;

            // Create new fee record (status defaults to pending/unpaid)
            Fee::create([
                'student_id'      => $student->id,
                'fee_type'        => $feeType,
                'amount'          => $amount,
                'fine_amount'     => 0,
                'discount_amount' => $discount,
                'due_date'        => $dueDate->format('Y-m-d'),
                'status'          => 'pending',
            ]);

            $count++;
        }

        $this->info("Successfully generated {$count} monthly fee records. Skipped {$skipped} existing records.");
        return Command::SUCCESS;
    }
}

if (!function_exists('parseFloatOrZero')) {
    function parseFloatOrZero($value) {
        return is_numeric($value) ? (float)$value : 0.0;
    }
}
