<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Bangladesh', 'code' => 'BD', 'phone_code' => '+880'],
            ['name' => 'United States', 'code' => 'US', 'phone_code' => '+1'],
            ['name' => 'India', 'code' => 'IN', 'phone_code' => '+91'],
            ['name' => 'United Kingdom', 'code' => 'GB', 'phone_code' => '+44'],
            ['name' => 'Canada', 'code' => 'CA', 'phone_code' => '+1'],
            ['name' => 'Australia', 'code' => 'AU', 'phone_code' => '+61'],
            ['name' => 'United Arab Emirates', 'code' => 'AE', 'phone_code' => '+971'],
            ['name' => 'Saudi Arabia', 'code' => 'SA', 'phone_code' => '+966'],
            ['name' => 'Malaysia', 'code' => 'MY', 'phone_code' => '+60'],
            ['name' => 'Singapore', 'code' => 'SG', 'phone_code' => '+65'],
        ];

        foreach ($countries as $countryData) {
            $country = Country::create($countryData);

            $stateNames = match ($country->code) {
                'BD' => ['Dhaka', 'Chittagong', 'Khulna', 'Rajshahi', 'Sylhet', 'Barisal', 'Rangpur', 'Mymensingh'],
                'US' => ['California', 'Texas', 'New York', 'Florida', 'Illinois', 'Ohio', 'Georgia'],
                'IN' => ['Delhi', 'Maharashtra', 'Karnataka', 'Tamil Nadu', 'Uttar Pradesh', 'West Bengal', 'Gujarat'],
                default => ['State 1', 'State 2', 'State 3'],
            };

            foreach ($stateNames as $stateName) {
                $state = State::create(['country_id' => $country->id, 'name' => $stateName]);

                $cityNames = match ($stateName) {
                    'Dhaka' => ['Dhaka', 'Gazipur', 'Narayanganj', 'Savar'],
                    'Chittagong' => ['Chittagong', 'Cox\'s Bazar', 'Rangamati'],
                    'California' => ['Los Angeles', 'San Francisco', 'San Diego', 'Sacramento'],
                    'Texas' => ['Houston', 'Dallas', 'Austin', 'San Antonio'],
                    'New York' => ['New York City', 'Buffalo', 'Rochester'],
                    default => ['City 1', 'City 2'],
                };

                foreach ($cityNames as $cityName) {
                    City::create(['state_id' => $state->id, 'name' => $cityName]);
                }
            }
        }
    }
}
