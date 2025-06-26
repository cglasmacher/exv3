<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceFieldsToQuotesTable extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->decimal('ek_net', 10, 2)->nullable()->after('price');
            $table->decimal('ek_gross', 10, 2)->nullable()->after('ek_net');
            $table->decimal('vk_net', 10, 2)->nullable()->after('ek_gross');
            $table->decimal('vk_gross', 10, 2)->nullable()->after('vk_net');
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['ek_net','ek_gross','vk_net','vk_gross']);
        });
    }
}