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
        // 1) user_id optional machen
        $table->dropForeign(['user_id']);
        $table->foreignId('user_id')
              ->nullable()
              ->change()
              ->constrained()
              ->cascadeOnDelete();

        // 2) guest_token ergänzen
        $table->string('guest_token', 36)
              ->nullable()
              ->after('user_id')
              ->unique();
    });
}

public function down(): void
{
    Schema::table('quote_requests', function (Blueprint $table) {
        // guest_token entfernen
        $table->dropUnique(['guest_token']);
        $table->dropColumn('guest_token');

        // user_id wieder not-null
        $table->dropForeign(['user_id']);
        $table->foreignId('user_id')
              ->nullable(false)
              ->change()
              ->constrained()
              ->cascadeOnDelete();
    });
}
};
