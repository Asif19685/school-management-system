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
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
    // Student table ki ID iske andar bhi aayegi

    $table->unsignedBigInteger('student_id')->nullable()->index();
    $table->string('document_title'); // e.g., 'Birth Certificate', 'Father CNIC'
    $table->string('file_path'); // Folder ka path: e.g., 'uploads/student_documents/doc.pdf'
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_documents');
    }
};
