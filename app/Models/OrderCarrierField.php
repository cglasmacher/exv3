<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderCarrierField extends Model
{
    use HasFactory;

    protected $fillable = ['carrier_response_id','field_key','field_value'];

    public function response(): BelongsTo
    {
        return $this->belongsTo(OrderCarrierResponse::class, 'carrier_response_id');
    }
}