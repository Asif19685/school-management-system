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
        // 1. MAIN EXAMS TABLE (Isme Year aur Type track hoga)
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('exam_name'); // e.g., "First Term", "Annual Exam 2026"
            $table->string('exam_type', 20)->index(); // e.g., 'mid_term', 'final_term', 'monthly_test'
            $table->year('academic_year')->index();   // History tracking ki jaan hai ye
            $table->unsignedBigInteger('class_id')->nullable()->index(); // Specific class ke liye scope
            $table->date('exam_date')->nullable()->index();
            $table->timestamps();
        });

        // 2. SUBJECT-WISE RESULTS TABLE (Har saal ke har subject ka score)
        Schema::create('subject_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('class_id')->index(); // Ye batayega us saal student kis class me tha
            $table->unsignedBigInteger('subject_id')->index();
            
            $table->integer('total_marks')->default(100);
            $table->integer('obtained_marks')->default(0);
            $table->string('grade', 5)->nullable();
            
            // 👈 STATUS ENUM (Sirf pass aur fail accept karega)
            $table->enum('status', ['pass', 'fail'])->default('pass')->index(); 
            
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Professional Indexes for super fast search history
            $table->index(['student_id', 'class_id']); 
            $table->index(['exam_id', 'student_id']);
        });

        // 3. FINAL/OVERALL RESULTS TABLE (Poore saal ka grand total record)
        Schema::create('final_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('class_id')->index(); // 2024 ki class 8, 2025 ki class 9 ka farq yahan se dikhega
            
            $table->integer('grand_total_marks')->default(0);
            $table->integer('grand_obtained_marks')->default(0);
            $table->decimal('percentage', 5, 2)->default(0.00); 
            $table->string('final_grade', 5)->nullable();
            
            // 👈 FINAL STATUS ENUM (Poore exam ka overall status)
            $table->enum('final_status', ['pass', 'fail'])->default('pass')->index(); 
            
            $table->integer('position')->nullable(); // Us saal class me position
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Fast indexing for student profile history tab
            $table->index(['student_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_results');
        Schema::dropIfExists('subject_results');
        Schema::dropIfExists('exams');
    }
};
