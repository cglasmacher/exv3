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
    Schema::create('quotes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('quote_request_id')->constrained()->cascadeOnDelete();
        $table->foreignId('service_id')->constrained()->cascadeOnDelete();
        $table->decimal('price', 10, 2);
        $table->string('currency', 3)->default('EUR');
        $table->integer('delivery_time_days')->nullable();
        $table->timestamp('expires_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
