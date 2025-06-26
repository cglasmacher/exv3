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
    Schema::create('order_carrier_fields', function (Blueprint $table) {
        $table->id();
        $table->foreignId('carrier_response_id')
              ->constrained('order_carrier_responses')
              ->cascadeOnDelete();
        $table->string('field_key');       // z.B. 'shipment_id', 'tracking_url', 'label_number'
        $table->text('field_value');       // z.B. 'JJD123456789', 'https://...'
        $table->timestamps();

        // schneller Zugriff auf häufige Felder:
        $table->index(['field_key']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_carrier_fields');
    }
};
