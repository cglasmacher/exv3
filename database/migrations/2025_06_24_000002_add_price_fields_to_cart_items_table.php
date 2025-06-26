<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceFieldsToCartItemsTable extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->decimal('price_net', 10, 2)->nullable()->after('quantity');
            $table->decimal('price_gross', 10, 2)->nullable()->after('price_net');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn(['price_net','price_gross']);
        });
    }
}