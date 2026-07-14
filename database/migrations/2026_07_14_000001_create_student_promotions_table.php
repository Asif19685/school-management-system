<?php
// database/migrations/2026_07_14_000001_create_student_promotions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_promotions', function (Blueprint $table) {
            $table->id();
            
            // Student reference (without foreign key constraint)
            $table->unsignedBigInteger('student_id');
            
            // Class references (without foreign key constraints)
            $table->unsignedBigInteger('from_class_id');
            $table->unsignedBigInteger('to_class_id');
            
            // Section references (nullable, without foreign key constraints)
            $table->unsignedBigInteger('from_section_id')->nullable();
            $table->unsignedBigInteger('to_section_id')->nullable();
            
            // Promotion details
            $table->string('academic_year', 20); // e.g., '2025-26'
            $table->date('promotion_date');
            $table->enum('status', ['Promoted', 'Failed', 'Transferred'])->default('Promoted');
            $table->text('remarks')->nullable();
            
            // Who performed this action
            $table->unsignedBigInteger('created_by');
            
            $table->timestamps();
            
            // Indexes for better performance (no foreign keys)
            $table->index('student_id');
            $table->index('from_class_id');
            $table->index('to_class_id');
            $table->index('academic_year');
            $table->index('status');
            $table->index('promotion_date');
            $table->index('created_by');
            
            // Composite index for quick lookups
            $table->index(['student_id', 'academic_year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_promotions');
    }
};
