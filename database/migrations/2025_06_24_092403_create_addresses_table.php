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
    Schema::create('addresses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete();
        $table->enum('type', ['pickup','delivery','billing']);
        $table->string('label')->nullable();   // z.B. „Hauptlager“, „Privat“
        $table->string('country', 2)->default('DE');
        $table->string('postcode', 10);
        $table->string('city');
        $table->string('street');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('addresses');
}
};
