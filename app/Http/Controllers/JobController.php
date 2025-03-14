<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApply;
use App\Models\JobSeeker;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
class JobController extends Controller
{
    public function getJobsList($pageNumber)
    {
        try {
            // Fetch all public jobs with employer, category, and country details
            $jobs = Job::where('visibility', 'public')
                ->where('employment_status', 'open')
                ->with(['employer:*', 'category:id,name', 'country:id,name'])
                ->orderBy('created_at', 'desc') // Order by latest jobs
                ->paginate(10, ['*'], 'page', $pageNumber);

            // Check if jobs exist
            if ($jobs->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No jobs found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Jobs retrieved successfully!',
                'data' => $jobs
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
