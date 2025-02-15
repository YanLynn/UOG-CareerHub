<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchCountry(Request $request){
        $query = $request->input('query');

        $countries = Country::where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name')
            ->limit(10)
            ->get();

        return response()->json($countries);
    }
}
