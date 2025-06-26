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
    Schema::table('quote_requests', function (Blueprint $table) {
        $table->dropColumn(['weight','length','width','height']);
    });
}

public function down(): void
{
    Schema::table('quote_requests', function (Blueprint $table) {
        $table->decimal('weight', 8, 2);
        $table->decimal('length', 8, 2)->nullable();
        $table->decimal('width',  8, 2)->nullable();
        $table->decimal('height', 8, 2)->nullable();
    });
}
};
