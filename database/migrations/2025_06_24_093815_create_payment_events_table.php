<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentEventsTable extends Migration
{
    public function up(): void
    {
        Schema::create('payment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')
                  ->constrained('payments')
                  ->cascadeOnDelete();
            $table->timestamp('event_time');
            $table->string('event_type');
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_events');
    }
}
