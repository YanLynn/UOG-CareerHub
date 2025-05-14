<?php

namespace App\Http\Controllers;

use App\Mail\JobApplicationApproved;
use App\Models\Employer;
use App\Models\Job;
use App\Models\JobApply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
class EmployerProfileController extends Controller
{
    public function getEmployerProfile()
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Fetch employer details
            $employer = Employer::select('*')
                ->where('user_id', Auth::id())
                ->with([
                    'jobs' => function ($query) {
                        $query->orderBy('created_at', 'desc')
                            ->with([
                                'category:id,name',
                                'country:*'  // Load country per job
                            ]);
                    },
                    'country:*'
                ])
                ->first();
            // If no employer found, return error response
            if (!$employer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employer profile not found'
                ], 404);
            }

            // Fetch total job stats
            $totalJobs = $employer->jobs->count();
            $activeJobs = $employer->jobs->where('employment_status', 'open')->count();
            $totalApplicants = JobApply::whereIn('job_id', $employer->jobs->pluck('id'))->count();

            // Structure response
            return response()->json([
                'success' => true,
                'message' => 'Employer profile retrieved successfully',
                'data' => [
                    'profile' => [
                        'company_name' => $employer->company_name,
                        'company_image' => $employer->company_image,
                        'industry' => $employer->industry,
                        'company_size' => $employer->company_size,
                        'founded_year' => $employer->founded_year,
                        'country' => $employer->country ?? null,
                        'company_description' => $employer->company_description,
                        'contact_email' => $employer->contact_email,
                        'contact_phone' => $employer->contact_phone,
                        'company_website' => $employer->company_website,
                        'linkedin_url' => $employer->linkedin_url,
                        'twitter_url' => $employer->twitter_url,
                        'facebook_url' => $employer->facebook_url,
                        'status' => $employer->status,
                        'verified' => $employer->verified,
                    ],
                    'metrics' => [
                        'total_jobs' => $totalJobs,
                        'active_jobs' => $activeJobs,
                        'total_applicants' => $totalApplicants,
                    ],
                    'jobs' => $employer->jobs
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch employer profile',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function createJobPost(Request $request)
    {
        try {

            $employer = Employer::where('user_id', Auth::id())->first();
            if (!$employer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employer profile not found for this user.'
                ], 404);
            }

            // Validate request data
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'job_title' => 'required|string|max:255',
                'description' => 'required|string',
                'country_id' => 'required|exists:countries,id',
                'salary' => 'required|numeric|min:0',
                'job_type' => 'required|in:full-time,part-time,contract',
                'job_location' => 'required|string|max:255',
                'experience_level' => 'required|string|max:255',
                'requirements' => 'required|string',
                'benefits' => 'required|string',
                'employment_status' => 'required|in:open,closed',
                'application_deadline' => 'nullable|date',
                'visibility' => 'required|in:public,private',
            ]);

            $job = Job::create([
                'employer_id' => $employer->id,
                'category_id' => $validated['category_id'],
                'job_title' => $validated['job_title'],
                'description' => $validated['description'],
                'country_id' => $validated['country_id'],
                'salary' => $validated['salary'],
                'job_type' => $validated['job_type'],
                'job_location' => $validated['job_location'],
                'experience_level' => $validated['experience_level'],
                'requirements' => $validated['requirements'],
                'benefits' => $validated['benefits'],
                'employment_status' => $validated['employment_status'],
                'application_deadline' => $validated['application_deadline']
                    ? Carbon::parse($validated['application_deadline'])->format('Y-m-d H:i:s')
                    : null,
                'visibility' => $validated['visibility'],
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Job post created successfully.',
                'data' => $job
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create job post.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteJob(Request $jID)
    {
        try {
            return $jID;
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete job post.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getJobById($jID)
    {

        try {
            $employer = Employer::where('user_id', Auth::id())->first();
            if (!$employer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employer profile not found for this user.'
                ], 404);
            }
            $job = Job::where('id', $jID)
                ->where('employer_id', $employer->id)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Job post retrieved successfully.',
                'data' => $job
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job post.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function updateJob(Request $request)
    {
        try {
            $employer = Employer::where('user_id', Auth::id())->first();

            if (!$employer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employer profile not found for this user.'
                ], 404);
            }

            $validatedData = $request->validate([
                'id' => 'required|exists:jobs,id',
                'job_title' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'country_id' => 'required|exists:countries,id',
                'currency_code' => 'required|string|max:5',
                'salary' => 'required|numeric',
                'job_type' => 'required|string',
                'job_location' => 'required|string|max:255',
                'experience_level' => 'required|string|max:255',
                'requirements' => 'required|string',
                'benefits' => 'required|string',
                'description' => 'required|string',
                'employment_status' => 'required|string',
                'application_deadline' => 'nullable|date',
                'visibility' => 'required|string',
            ]);

            // Find job owned by this employer
            $job = Job::where('id', $validatedData['id'])
                ->where('employer_id', $employer->id)
                ->firstOrFail();

            // Remove jId from the validated data before updating
            unset($validatedData['id']);

            $job->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Job post updated successfully.',
                'data' => $job
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $ve->errors()
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update job post.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getApplicationsByJob()
    {
        try {
            // 1. Get the current employer
            $employer = Employer::where('user_id', Auth::id())->first();

            if (!$employer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employer not found.'
                ], 404);
            }

            $applications = JobApply::whereHas('job', function ($query) use ($employer) {
                $query->where('employer_id', $employer->id);
            })
                ->with(['job', 'jobseeker.user'])
                ->orderBy('created_at', 'desc')
                ->get();


            return response()->json([
                'success' => true,
                'data' => $applications
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load job applications.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function updateJobApplicationStatus(Request $request)
    {


        $request->validate([
            'job_id' => 'required|integer',
            'jobseeker_id' => 'required|integer',
            'status' => 'required|in:approved,rejected',
        ]);

        $application = JobApply::where('job_id', $request->job_id)
            ->where('jobseeker_id', $request->jobseeker_id)
            ->first();

        if (!$application) {
            return response()->json(['message' => 'Application not found.'], 404);
        }

        $application->status = $request->status;
        $application->save();
        if ($request->status === 'approved') {
            $jobseekerEmail = $request->application['jobseeker']['user']['email'];
            Mail::to('ny.yanlynn@gmail.com')->send(new JobApplicationApproved($application));
        }

        return response()->json([
            'message' => "Application marked as {$request->status}.",
            'data' => $application
        ]);
    }
}
