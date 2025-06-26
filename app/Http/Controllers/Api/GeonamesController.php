<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeonamesController extends Controller
{
/**
* Return city names for a given country and postal code via GeoNames API
*/
    public function cities(Request $request)
    {
    $data = $request->validate([
    'country'    => 'required|string|size:2',
    'postalcode' => 'required|string',
    ]);

        $username = config('geonames.username');
        $response = Http::get('http://api.geonames.org/postalCodeSearchJSON', [
            'postalcode' => $data['postalcode'],
            'country'    => strtoupper($data['country']),
            'maxRows'    => 10,
            'username'   => $username,
        ]);

    if ($response->failed()) {
        return response()->json([], 502);
    }

    $postalCodes = $response->json('postalCodes', []);
    $cities = collect($postalCodes)
        ->pluck('placeName')
        ->unique()
        ->values();

    return response()->json($cities);
    }
}