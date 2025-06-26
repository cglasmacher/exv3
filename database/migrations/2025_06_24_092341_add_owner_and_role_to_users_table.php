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
    Schema::table('users', function (Blueprint $table) {
        $table->foreignId('owner_id')
              ->nullable()
              ->after('id')
              ->constrained('users')
              ->nullOnDelete();
        $table->enum('role', ['owner','orderer','viewer'])
              ->default('owner')
              ->after('owner_id');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['owner_id','role']);
    });
}
};
