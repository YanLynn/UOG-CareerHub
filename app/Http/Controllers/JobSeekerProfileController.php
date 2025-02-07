<?php

namespace App\Http\Controllers;

use App\Models\JobSeeker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobSeekerProfileController extends Controller
{
    public function getJobSeeker()
    {
        $authUserID = Auth::user();
        $jobSeeker = JobSeeker::with([
            'user:id,name,email',
            'country:id,name,postal_code'
        ])->where('user_id', $authUserID->id)->first();

        if (!$jobSeeker) {
            return response()->json([
                'success' => false,
                'message' => 'Job Seeker profile not found',
            ], 404);
        }
        $jobSeeker->educations = $jobSeeker->educations();
        $jobSeeker->skills = $jobSeeker->skills();
        $jobSeeker->languages = $jobSeeker->languages();
        $jobSeeker->careerHistories = $jobSeeker->careerHistories();

        return response()->json([
            'success' => true,
            'data' => $jobSeeker,
        ], 200);
    }
}
