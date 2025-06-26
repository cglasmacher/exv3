<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_request_id',
        'service_id',
        'price',
        'currency',
        'delivery_time_days',
        'expires_at',
        'ek_net',
        'ek_gross',
        'vk_net',
        'vk_gross',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function quoteRequest(): BelongsTo
    {
        return $this->belongsTo(QuoteRequest::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}