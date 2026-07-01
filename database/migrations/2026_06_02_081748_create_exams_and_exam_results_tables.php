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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('exam_name')->index();
             $table->unsignedBigInteger('class_id')->nullable()->index();
            $table->date('exam_date')->nullable()->index();
            $table->integer('total_marks')->default(100);
            $table->timestamps();
        });

        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
           $table->unsignedBigInteger('exam_id')->nullable()->index();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->unsignedBigInteger('subject_id')->nullable()->index();
            $table->integer('obtained_marks')->default(0);
            $table->string('grade', 5)->nullable()->index();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['exam_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('exams');
    }
};
