<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DimensionRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_type',
        'max_length',
        'max_width',
        'max_height',
        'max_weight',
    ];
}