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
    Schema::create('quote_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('quote_request_id')->constrained()->cascadeOnDelete();
        $table->enum('item_type', ['package','pallet','document']);
        $table->decimal('weight', 8, 2)->nullable();    // in kg
        $table->decimal('length', 8, 2)->nullable();    // in cm
        $table->decimal('width',  8, 2)->nullable();
        $table->decimal('height', 8, 2)->nullable();
        $table->unsignedInteger('quantity')->default(1);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_items');
    }
};
