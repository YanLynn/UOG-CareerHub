<?php

namespace App\Http\Controllers;

use App\Models\Category;
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

    public function getCountry(){
        try {

            $getCountry = Country::all();
            return response()->json([
                'success' => true,
                'message' => 'getCountry retrieved successfully!',
                'data' => $getCountry
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch jobs.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getCategory(){
        try {

            $getCategory = Category::all();
            return response()->json([
                'success' => true,
                'message' => 'getCountry retrieved successfully!',
                'data' => $getCategory
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch jobs.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
