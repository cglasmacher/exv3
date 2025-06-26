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
    Schema::create('order_carrier_responses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')
              ->constrained()
              ->cascadeOnDelete();
        $table->foreignId('carrier_id')
              ->constrained()
              ->cascadeOnDelete();
        $table->json('payload');            // komplette API-Antwort
        $table->timestamps();
        $table->unique(['order_id','carrier_id']); // höchstens eine aktuelle Response pro Carrier/Order
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_carrier_responses');
    }
};
