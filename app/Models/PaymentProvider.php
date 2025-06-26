<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'enabled',
        'api_key',
        'api_secret',
        'config',
        'endpoints',
    ];

    protected $casts = [
        'enabled'   => 'boolean',
        'config'    => 'array',
        'endpoints' => 'array',
    ];

    public function userMethods(): HasMany
    {
        return $this->hasMany(UserPaymentMethod::class, 'provider_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'provider_id');
    }
}