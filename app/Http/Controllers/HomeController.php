<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getCompany(){
        try {
            $getCompany = Employer::with('country')->get();
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $getCompany
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $th->getMessage()
            ], 500);
        }

    }
}
