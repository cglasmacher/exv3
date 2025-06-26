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
        $table->string('sender_country', 2)->default('DE')->after('user_id');
        $table->string('sender_postcode', 10)->change();   // falls du Länge anpassen möchtest
        $table->string('sender_city')->after('sender_postcode');

        $table->string('recipient_country', 2)->after('sender_city');
        $table->string('recipient_postcode', 10)->change();
        $table->string('recipient_city')->after('recipient_postcode');
    });
}

public function down(): void
{
    Schema::table('quote_requests', function (Blueprint $table) {
        $table->dropColumn([
            'sender_country',
            'sender_city',
            'recipient_country',
            'recipient_city',
        ]);
        // falls du die Länge der PLZ-Spalten geändert hast, stell sie hier zurück
    });
}
};
