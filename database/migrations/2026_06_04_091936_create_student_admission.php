<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_admissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->index(); // Connects with Students table

            // Initial Form Data
          
            $table->unsignedBigInteger('applied_class_id')->nullable()->index(); // Jis class k liye apply kia

            // Office Use & Approval Data (Fillable after approval)
            $table->string('admission_no')->unique()->nullable(); // Nullable until approved
            $table->unsignedBigInteger('class_id')->nullable()->index(); // Confirmed Class
            $table->unsignedBigInteger('section_id')->nullable()->index();

            $table->date('admission_date')->nullable();

            // Status Management Workflow
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->string('remarks')->nullable(); // Agar reject ho ya koi note likhna ho

            // Approving Authority Signatures Tracker
            $table->string('approved_by_officer')->nullable();
            $table->string('approved_by_head')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_admission');
    }
};
