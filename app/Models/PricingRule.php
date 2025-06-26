<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'base_price',
        'price_per_km',
        'price_per_kg',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}