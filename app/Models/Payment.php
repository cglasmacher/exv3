<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'provider_id',
        'user_payment_method_id',
        'amount',
        'currency',
        'status',
        'provider_reference',
        'payload',
        'paid_at',
        'refunded_at',
    ];

    protected $casts = [
        'payload'        => 'array',
        'paid_at'        => 'datetime',
        'refunded_at'    => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'provider_id');
    }

    public function userPaymentMethod(): BelongsTo
    {
        return $this->belongsTo(UserPaymentMethod::class, 'user_payment_method_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(PaymentEvent::class)
;    }
}