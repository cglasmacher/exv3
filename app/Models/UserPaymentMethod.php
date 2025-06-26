<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider_id',
        'method_key',
        'type',
        'details',
        'is_default',
    ];

    protected $casts = [
        'details'   => 'array',
        'is_default'=> 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'provider_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}