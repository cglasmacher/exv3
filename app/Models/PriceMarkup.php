<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceMarkup extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'weight_min',
        'weight_max',
        'markup_percent',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}