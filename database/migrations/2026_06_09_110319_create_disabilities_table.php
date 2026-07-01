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
        Schema::create('disabilities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Visual Impairment, Hearing Impairment, Mental Retardation
            $table->string('description')->nullable(); // System code ya short-form ke liye (e.g., VI, HI)
            $table->boolean('status')->default(true); // Active/Inactive tracking
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disabilities');
    }
};
