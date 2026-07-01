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
        Schema::create('student_images', function (Blueprint $table) {
           $table->id();
         $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('image_name'); // File ka asal naam
            $table->string('image_path'); // Folder ka path: uploads/student_images/filename.jpg
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_images');
    }
};
