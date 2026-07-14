<?php
// database/migrations/2026_07_14_000002_add_academic_year_to_student_admissions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_admissions', function (Blueprint $table) {
            $table->string('academic_year', 20)->nullable()->after('admission_date');
            $table->index('academic_year');
        });
    }

    public function down()
    {
        Schema::table('student_admissions', function (Blueprint $table) {
            $table->dropColumn('academic_year');
        });
    }
};
