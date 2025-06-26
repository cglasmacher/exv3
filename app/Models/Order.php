<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'user_id',
        'billing_address_id',
        'external_shipment_id',
        'tracking_number',
        'tracking_url',
        'total_price',
        'status',
        'placed_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'placed_at'    => 'datetime',
        'shipped_at'   => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function labels(): HasMany
    {
        return $this->hasMany(Label::class);
    }

    public function carrierResponses(): HasMany
    {
        return $this->hasMany(OrderCarrierResponse::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}