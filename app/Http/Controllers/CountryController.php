<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CountryController extends Controller
{
    public function getCountries()
    {
        return response()->json(Country::all());
    }
}
