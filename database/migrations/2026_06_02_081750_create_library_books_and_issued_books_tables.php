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
        Schema::create('library_books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('author')->nullable()->index();
            $table->string('isbn')->nullable()->unique();
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });

        Schema::create('issued_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('library_books')->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->date('issue_date')->index();
            $table->date('return_date')->nullable()->index();
            $table->enum('status', ['issued', 'returned', 'overdue'])->default('issued')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issued_books');
        Schema::dropIfExists('library_books');
    }
};
