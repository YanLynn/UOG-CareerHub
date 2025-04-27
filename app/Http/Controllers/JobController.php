<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApply;
use App\Models\JobSeeker;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use Carbon\Carbon;

class JobController extends Controller
{
    public function getJobsList(Request $request)
    {
        try {
            // Retrieve query parameters
            $pageNumber = (int) $request->input('page', 1);
            $keyword = trim($request->input('keyword', ''));
            $location = $request->input('location', null);
            $category = $request->input('category', null);
            $jobType = $request->input('jobType', null);

            // Query jobs with necessary joins
            $query = Job::select([
                'jobs.*',
                'employers.*',
                'categories.id as category_id',
                'categories.name as category_name',
                'countries.*'
            ])
                ->join('employers', 'jobs.employer_id', '=', 'employers.id')
                ->join('categories', 'jobs.category_id', '=', 'categories.id')
                ->leftJoin('countries', 'jobs.country_id', '=', 'countries.id')
                ->where('jobs.visibility', 'public')
                ->where('jobs.employment_status', 'open');

            // Apply keyword search on job title or employer name
            if (!empty($keyword)) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('jobs.job_title', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('employers.company_name', 'LIKE', '%' . $keyword . '%');
                });
            }

            if (!empty($jobType)) {
                $query->where(function ($q) use ($jobType) {
                    $q->where('jobs.job_type', 'LIKE', '%' . $jobType . '%');
                });
            }

            // Filter by country name
            if (!empty($location)) {
                $query->where('countries.country', 'LIKE', '%' . $location . '%');
            }

            // Filter by category name
            if (!empty($category)) {
                $query->where('categories.name', 'LIKE', '%' . $category . '%');
            }

            // Order by latest jobs and paginate
            $jobs = $query->orderBy('jobs.created_at', 'desc')
                ->paginate(9, ['*'], 'page', $pageNumber);

            // Convert jobs collection into a multi-dimensional array
            $jobList = $jobs->map(function ($job) {
                return [
                    'id' => $job->id,
                    'job_title' => $job->job_title,
                    'description' => $job->description,
                    'salary' => $job->salary,
                    'job_type' => $job->job_type,
                    'job_location' => $job->job_location,
                    'experience_level' => $job->experience_level,
                    'requirements' => $job->requirements,
                    'employment_status' => $job->employment_status,
                    'visibility' => $job->visibility,
                    'isApplied' => false, // Default
                    'isSaved' => false, // Default
                    'application_deadline' => $job->application_deadline,
                    'benefits' => $job->benefits,
                    'created_at' => $job->created_at,
                    'updated_at' => $job->updated_at,

                    'employer' => [
                        'id' => $job->employer_id,
                        'company_name'=>$job->company_name,
                        'company_website'=>$job->company_website,
                        'company_image'=>$job->company_image,
                        'industry'=>$job->industry,
                        'company_size'=>$job->company_size,
                        'company_description'=>$job->company_description,
                        'founded_year'=>$job->founded_year,
                        'country_id'=>$job->country_id,
                        'contact_email'=>$job->contact_email,
                        'contact_phone'=>$job->contact_phone,
                        'linkedin_url'=>$job->linkedin_url,
                        'twitter_url'=>$job->twitter_url,
                        'facebook_url'=>$job->facebook_url,
                        'status'=>$job->status,
                        'verified'=>$job->verified,
                    ],

                    'category' => [
                        'id' => $job->category_id,
                        'name' => $job->category_name,
                    ],


                    'country' => [
                        'id' => $job->id,
                        'name' => $job->name,
                        'country' => $job->country,
                        'code' => $job->code,
                        'countryCode' => $job->countryCode,
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Jobs retrieved successfully!',
                'data' => [
                    'current_page' => $jobs->currentPage(),
                    'data' => $jobList,
                    'total' => $jobs->total(),
                    'per_page' => $jobs->perPage(),
                    'last_page' => $jobs->lastPage(),
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch jobs.',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function checkJobApplied()
    {
        try {
            $getJobApply = collect();

            if (Auth::check()) {
                $getJobSeeker = JobSeeker::where('user_id', Auth::id())->value('id');

                if ($getJobSeeker) {
                    $getJobApply = JobApply::where('jobseeker_id', $getJobSeeker)
                        ->select('job_id', 'status', 'type')
                        ->get();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Jobs retrieved successfully!',
                'data' => $getJobApply
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch applied jobs.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function jobApplyAndSave(Request $req)
    {
        try {
            //  Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not authenticated'
                ], 401);
            }

            //  Validate required input fields
            if (!$req->has('jobId')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing job ID'
                ], 400);
            }

            $jobID = $req->jobId;
            $status = $req->status ?? null; // May be null
            $type = $req->type ?? null;     // 'saved' or 'unsaved'

            //  Retrieve job seeker ID
            $getJobSeekerID = JobSeeker::where('user_id', Auth::id())->value('id');
            if (!$getJobSeekerID) {
                return response()->json([
                    'success' => false,
                    'message' => 'Job seeker profile not found'
                ], 404);
            }

            //  Check if the user already has a job application or saved job entry
            $findJobApply = JobApply::where('job_id', $jobID)
                ->where('jobseeker_id', $getJobSeekerID)
                ->first();

            // 🔹 Case 1: Applying for a job for the first time (not yet saved or applied)
            if (!$findJobApply && $status && !$type) {
                $jobApply = new JobApply();
                $jobApply->job_id = $jobID;
                $jobApply->jobseeker_id = $getJobSeekerID;
                $jobApply->status = $status; // 'pending', 'approved', etc.
                $jobApply->type = null; // Default type
                $jobApply->job_apply_date = Carbon::now(); // Only for applications
                $jobApply->save();

                return response()->json([
                    'success' => true,
                    'message' => 'new Job applied table successfully applied!',
                    'data' => $jobApply
                ], 201);
            }

            // 🔹 Case 2: Saving a job that hasn’t been applied for yet
            if (!$findJobApply && !$status && $type) {
                $jobApply = new JobApply();
                $jobApply->job_id = $jobID;
                $jobApply->jobseeker_id = $getJobSeekerID;
                $jobApply->status = null;
                $jobApply->type = 'saved';
                $jobApply->save();

                return response()->json([
                    'success' => true,
                    'message' => 'new Job applied table successfully saved!',
                    'data' => $jobApply
                ], 201);
            }

            if ($findJobApply && $status && !$type) {

                $findJobApply->status = $status;
                $findJobApply->job_apply_date = Carbon::now();
                // $jobApply->type = 'saved';
                $findJobApply->save();

                return response()->json([
                    'success' => true,
                    'message' => 'already exits Job applied table successfully applied!',
                    'data' => $findJobApply
                ], 201);
            }


            // 🔹 Case 3: Updating an existing saved job (saved → unsaved or vice versa)
            if ($findJobApply && ($type === 'saved' || $type === 'unsaved')) {
                $findJobApply->type = $type;
                $findJobApply->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Job saved status updated successfully!',
                    'data' => $findJobApply
                ], 200);
            }


            // 🔹 Fallback case: No valid action taken
            return response()->json([
                'success' => false,
                'message' => 'No action performed. Please check input values.'
            ], 400);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process job application/save',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
