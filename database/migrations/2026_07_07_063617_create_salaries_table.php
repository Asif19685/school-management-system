<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('salaries', function (Blueprint $table) {
            $table->id(); // Auto Increment Primary Key

            // Foreign Key linking to Teachers table
           $table->unsignedBigInteger('teacher_id')->nullable()->index();

            $table->string('month_year'); // Format: "07-2026"

            // Decimal values for money/salary data (10 digits total, 2 decimal places)
            $table->decimal('base_salary', 10, 2);

            // Counters for attendance
            $table->integer('total_present')->default(0);
            $table->integer('total_absent')->default(0);
            $table->integer('total_half_days')->default(0);

            // Financial calculations
            $table->decimal('deductions', 10, 2)->default(0.00);
            $table->decimal('net_salary', 10, 2); // Final Payable Salary

            // Status for payroll tracking
            $table->enum('status', ['Pending', 'Paid'])->default('Pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
