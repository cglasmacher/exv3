<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder loads country names and ISO codes from the umpirsky/country-list package.
     */
    public function run(): void
    {
        // Path to the JSON file provided by umpirsky
        $jsonPath = base_path('vendor/umpirsky/country-list/data/de_DE/country.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("Country JSON file not found at {$jsonPath}. Have you installed umpirsky/country-list?");
            return;
        }

        $countries = json_decode(File::get($jsonPath), true);

        foreach ($countries as $iso => $name) {
            Country::updateOrCreate([
                'iso_code' => $iso,
            ], [
                'name'     => $name,
            ]);
        }

        $this->command->info('Seeded ' . Country::count() . ' countries.');
    }
}