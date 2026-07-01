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
       Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->string('father_name');
            $table->string('father_cnic')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_education')->nullable();
            $table->string('family_monthly_income')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('emergency_contact', 20)->nullable();
            $table->text('complete_address')->nullable();
            $table->text('postal_address')->nullable();
            $table->timestamps();
        });

       Schema::create('students', function (Blueprint $table) {
    $table->id();
    $table->string('registration_no')->unique()->nullable();
    $table->string('first_name');
    $table->string('last_name')->nullable();
    $table->string('gender', 20)->nullable();
    $table->date('dob')->nullable();
    $table->string('religion')->nullable();
    $table->string('b_form_no')->nullable()->index();

    // Disabilities Dropdown Connection
    $table->unsignedBigInteger('disability_id')->nullable()->index();
    $table->string('additional_disability')->nullable();
    $table->string('disability_certificate_no')->nullable();

    // Previous Schooling (Inko hum "nullable" rakh rahy hain, compulsory nahi hain)
    $table->text('previous_school_details')->nullable();
    $table->string('previous_class')->nullable();
    $table->string('cause_of_leaving_school')->nullable();

    $table->unsignedBigInteger('guardian_id')->nullable()->index();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
        Schema::dropIfExists('guardians');
    }
};
