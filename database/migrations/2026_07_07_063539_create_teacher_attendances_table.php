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
        Schema::create('teacher_attendances', function (Blueprint $table) {
          $table->id(); // Auto Increment Primary Key

            // Foreign Key (Links to teachers table)
            // Note: Agar aapka teachers table ka naam 'teachers' hai to yeh automatically map ho jayega
            $table->unsignedBigInteger('teacher_id')->nullable()->index();

            $table->date('date'); // Attendance Date
            $table->time('check_in')->nullable(); // Entry time (nullable kyun ke absent par time nahi hoga)
            $table->time('check_out')->nullable(); // Exit time

            // Status Column with ENUM values
            $table->enum('status', ['Present', 'Absent', 'Leave', 'Half-Day'])->default('Present');

            $table->timestamps(); // created_at aur updated_at ke liye
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_attendances');
    }
};
