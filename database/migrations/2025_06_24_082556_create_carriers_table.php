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
    Schema::create('carriers', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->string('api_key')->nullable();
        $table->string('api_secret')->nullable();
        $table->json('endpoints')->nullable(); // z.B. { "rates": "...", "label": "..." }
        $table->boolean('enabled')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carriers');
    }
};
