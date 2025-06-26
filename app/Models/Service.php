<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'carrier_id',
        'name',
        'service_code',
        'delivery_days',
    ];

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class);
    }

    public function pricingRule(): HasOne
    {
        return $this->hasOne(PricingRule::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}