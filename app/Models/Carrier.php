<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_key',
        'api_secret',
        'endpoints',
        'enabled',
    ];

    protected $casts = [
        'endpoints' => 'array',
        'enabled'   => 'boolean',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function orderResponses(): HasMany
    {
        return $this->hasMany(OrderCarrierResponse::class);
    }
}