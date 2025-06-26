<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'iso_code'];
}

// database/data/countries.json
// (place this file with an array of all countries and their ISO codes)
// [
//   {"name": "Germany", "iso_code": "DE"},
//   {"name": "Austria", "iso_code": "AT"},
//   ...
// ]