<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceMarkupsTable extends Migration
{
    public function up(): void
    {
        Schema::create('price_markups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->decimal('weight_min', 8, 2)->default(0);
            $table->decimal('weight_max', 8, 2)->nullable();
            $table->decimal('markup_percent', 5, 2); // e.g. 10.00 = 10%
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_markups');
    }
}