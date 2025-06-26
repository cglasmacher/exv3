<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderCarrierResponse extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','carrier_id','payload'];

    protected $casts = ['payload' => 'array'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(OrderCarrierField::class, 'carrier_response_id');
    }
}