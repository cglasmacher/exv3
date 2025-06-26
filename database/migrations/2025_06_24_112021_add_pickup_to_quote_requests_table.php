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
        // Optionale Abholadresse aus dem Adressbuch
        $table->foreignId('pickup_address_id')
              ->nullable()
              ->after('guest_token')
              ->constrained('addresses')
              ->nullOnDelete();
        
        // Gewünschtes Abholdatum und -zeit
        $table->date('preferred_pickup_date')
              ->nullable()
              ->after('pickup_address_id');
        $table->time('preferred_pickup_time')
              ->nullable()
              ->after('preferred_pickup_date');
    });
}

public function down(): void
{
    Schema::table('quote_requests', function (Blueprint $table) {
        $table->dropForeign(['pickup_address_id']);
        $table->dropColumn([
            'pickup_address_id',
            'preferred_pickup_date',
            'preferred_pickup_time',
        ]);
    });
}
};
