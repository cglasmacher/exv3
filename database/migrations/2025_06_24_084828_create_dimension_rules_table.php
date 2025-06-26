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
    Schema::create('dimension_rules', function (Blueprint $table) {
        $table->id();
        $table->enum('item_type', ['package','pallet']);
        $table->decimal('max_length', 8, 2)->nullable();    // in cm
        $table->decimal('max_width',  8, 2)->nullable();
        $table->decimal('max_height', 8, 2)->nullable();
        $table->decimal('max_weight', 8, 2)->nullable();    // in kg
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dimension_rules');
    }
};
