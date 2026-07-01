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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
           $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('fee_type')->index();
            $table->decimal('amount', 10, 2);
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->date('due_date')->nullable()->index();
            $table->enum('status', ['pending', 'paid', 'partial'])->default('pending')->index();
            $table->timestamps();
        });

        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
          $table->unsignedBigInteger('fee_id')->nullable()->index();
            $table->decimal('paid_amount', 10, 2);
            $table->date('payment_date')->index();
            $table->string('payment_method')->nullable()->index();
            $table->string('receipt_no')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
        Schema::dropIfExists('fees');
    }
};
