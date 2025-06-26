<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'event_time',
        'event_type',
        'data',
    ];

    protected $casts = [
        'event_time' => 'datetime',
        'data'       => 'array',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}