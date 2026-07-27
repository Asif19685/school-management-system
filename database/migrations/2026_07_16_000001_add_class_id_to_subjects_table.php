<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Subjects ko Course ki bajaye Class se link karna
     */
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // class_id add karo (school class se linked)
            $table->unsignedBigInteger('class_id')->nullable()->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('class_id');
        });
    }
};
