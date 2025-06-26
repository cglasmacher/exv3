<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('provider_id')
                  ->constrained('payment_providers')
                  ->cascadeOnDelete();
            $table->foreignId('user_payment_method_id')
                  ->nullable()
                  ->constrained('user_payment_methods')
                  ->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('EUR');
            $table->enum('status', ['pending','succeeded','failed','refunded'])->default('pending');
            $table->string('provider_reference')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}
