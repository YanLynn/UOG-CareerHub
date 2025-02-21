<?php

namespace App\Http\Controllers;

use App\Models\CareerHistory;
use App\Models\Education;
use App\Models\JobSeeker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\User;
use App\Services\ImageUploadService;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\Skill;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\JobApply;
class JobSeekerProfileController extends Controller
{
    public function getJobSeeker()
    {
        $authUserID = Auth::user();
        $jobSeeker = JobSeeker::with([
            'user:id,name,email',
            'country:id,name,code'
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

    public function updateProfile(Request $req)
    {

        try {
            $getJSLoginUsr = Auth::user();
            if (!$getJSLoginUsr) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            $jobSeeker = JobSeeker::where('user_id', $getJSLoginUsr->id)->first();
            if (!$jobSeeker) {
                return response()->json([
                    'success' => false,
                    'message' => 'Job Seeker profile not found'
                ], 404);
            }

            $imageUrl = $jobSeeker->profile_img;
            if ($req->has('profile_image') && str_starts_with($req->profile_image, 'data:image')) {
                try {

                    $imageUrl = ImageUploadService::uploadBase64Image($req->profile_image);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cloudinary upload error',
                        'error' => $e->getMessage()
                    ], 500);
                }
            }

            $jobSeeker->update([
                'country_id' => $req->location,
                'profile_img' => $imageUrl
            ]);

            User::where('id', Auth::user()->id)->update([
                'name' => $req->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $jobSeeker
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $th->getMessage()
            ], 500);
        }
    }



    public function updateSkill(Request $req)
    {

        try {

            $skills = json_decode($req->getContent(), true);
            $skills = array_map(function ($skill) {
                return strtolower(trim($skill));
            }, $skills);
            $existingSkillModels = Skill::whereIn(DB::raw('LOWER(name)'), $skills)->get();
            $existingSkillNames = $existingSkillModels->pluck('name')->map(fn($name) => strtolower($name))->toArray();
            $newSkillNames = array_diff($skills, $existingSkillNames);
            if (!empty($newSkillNames)) {
                foreach ($newSkillNames as $newName) {
                    $newSkill =  Skill::create(['name' => $newName]);
                    $existingSkillModels->push($newSkill);
                }
            }
            $allSkillIds = $existingSkillModels->pluck('id')->toArray();
            $finalSkillsString = implode(',', $allSkillIds);
            JobSeeker::where('user_id', Auth::id())->update([
                'skill_id' => $finalSkillsString
            ]);

            return response()->json([
                'message'         => 'Skills updated successfully!',
                'final_skill_ids' => $allSkillIds,
                'new_skills'      => array_values($newSkillNames), // optional info
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update skills',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function uploadResumeFile(Request $req)
    {

        try {

            $req->validate([
                'resume' => 'required|mimes:pdf,doc,docx|max:10000',
            ]);

            if (!$req->hasFile('resume')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No document uploaded.',
                ], 400);
            }
            $resume = $req->file('resume');
            $fileName = $req->file_name;
            $uploadedFile = Cloudinary::upload(
                $resume->getRealPath(), // Ensure a valid file path
                [
                    'folder' => 'documents',
                    'timeout' => 120
                ]
            );

            $fileUrl = $uploadedFile->getSecurePath();

            if ($fileUrl) {
                JobSeeker::where('user_id', Auth::user()->id)->update([
                    "resume_url" => $fileUrl,
                    "resume_file_name" => $fileName,
                ]);
            }
            return response()->json([
                'success' => true,
                'url' => $fileUrl,
                'message' => 'Document uploaded successfully!',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update document.',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function addWorkExp(Request $req)
    {
        try {
            if (!empty($req->job_id)) {
                $careerHistory = CareerHistory::find($req->job_id);
                if (!$careerHistory) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Career history record not found.',
                    ], 404);
                }
                $careerHistory->update([
                    'job_title'     => $req->job_title,
                    'start_date'    => $req->startDate,
                    'end_date'      => $req->endDate ?: null,
                    'description'   => $req->description,
                    'still_in_role' => empty($req->endDate) ? 1 : 0,
                ]);
            } else {
                $careerHistory = new CareerHistory();
                $careerHistory->job_title = $req->job_title;
                $careerHistory->start_date = $req->startDate;
                $careerHistory->end_date = $req->endDate ?: null;
                $careerHistory->description = $req->description;
                $careerHistory->still_in_role = empty($req->endDate) ? 1 : 0;
                $careerHistory->save();

                $newCareerHistoryId = $careerHistory->id;
                $jobSeeker = JobSeeker::where('user_id', Auth::user()->id)->first();
                $existingIDs = explode(',', $jobSeeker->career_history_id);
                if (!in_array($newCareerHistoryId, $existingIDs)) {
                    $existingIDs[] = $newCareerHistoryId;
                }
                $jobSeeker->career_history_id = implode(',', $existingIDs);
                $jobSeeker->save();
            }

            return response()->json([
                'success' => true,
                'data'    => $careerHistory,
                'message' => 'Career history saved successfully!',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update history.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteCareerHistory(Request $req)
    {
        try {
            if ($req) {
                $careerId = json_decode($req->getContent(), true);
                CareerHistory::where('id', $careerId)->delete();
                $jobSeeker = JobSeeker::where('user_id', Auth::user()->id)->first();
                if ($jobSeeker) {
                    if (!empty($jobSeeker->career_history_id)) {
                        $existingIDs = explode(',', $jobSeeker->career_history_id);
                        $updatedIDs = array_filter($existingIDs, function ($id) use ($careerId) {
                            return $id != $careerId;
                        });
                        $jobSeeker->career_history_id = implode(',', $updatedIDs);
                        $jobSeeker->save();
                    }
                }
            }
            return response()->json(['message' => 'Career history deleted.']);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update history.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function saveEducation(Request $req)
    {

        try {
            if (!empty($req->id)) {
                $edu = Education::find($req->id);
                if (!$edu) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Education record not found.',
                    ], 404);
                }
                $edu->update([
                    'qualification' => $req->degree,
                    'institution' => $req->school,
                    //year issue from front-end
                    'date' => Carbon::parse($req->year)->year + 1 ?: null,
                    'qualification_status' => empty($req->year) ? 1 : 0
                ]);
            } else {
                $edu = new Education();
                $edu->qualification = $req->degree;
                $edu->institution = $req->school;
                $edu->date = Carbon::parse($req->year)->year + 1 ?: null;
                $edu->qualification_status = empty($req->year) ? 1 : 0;
                $edu->save();

                $eduID = $edu->id;
                $jobSeeker = JobSeeker::where('user_id', Auth::user()->id)->first();
                $existingIDs = explode(',', $jobSeeker->education_id);
                if (!in_array($eduID, $existingIDs)) {
                    $existingIDs[] = $eduID;
                }
                $jobSeeker->education_id = implode(',', $existingIDs);
                $jobSeeker->save();
            }

            return response()->json([
                'success' => true,
                'data'    => $edu,
                'message' => 'Education saved successfully!',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Education.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteEducation($eduID)
    {
        try {
            if ($eduID) {

                Education::where('id', $eduID)->delete();
                $jobSeeker = JobSeeker::where('user_id', Auth::user()->id)->first();
                if ($jobSeeker) {
                    if (!empty($jobSeeker->education_id)) {
                        $existingIDs = explode(',', $jobSeeker->education_id);
                        $updatedIDs = array_filter($existingIDs, function ($id) use ($eduID) {
                            return $id != $eduID;
                        });
                        $jobSeeker->education_id = implode(',', $updatedIDs);
                        $jobSeeker->save();
                    }
                }
            }
            return response()->json(['message' => 'Education deleted.']);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Education.',
                'error' => $th->getMessage()
            ], 500);
        }
    }




    public function jobSeekerJobList($status)
    {
        try {
            $jobSeeker = JobSeeker::where('user_id', Auth::user()->id)->first();

            $applications = JobApply::select([
                'job_applies.*',
                'jobs.*',
                'employers.*',
                'categories.name as category_name',
                'countries.name as country_name',
            ])
                ->join('jobs', 'job_applies.job_id', '=', 'jobs.id')
                ->join('employers', 'jobs.employer_id', '=', 'employers.id')
                ->join('categories', 'jobs.category_id', '=', 'categories.id')
                ->leftJoin('countries', 'jobs.country_id', '=', 'countries.id')
                ->where('job_applies.jobseeker_id', $jobSeeker->user_id)
                ->when($status, function ($query) use ($status) {
                    if (in_array($status, ['pending', 'approved', 'saved'])) {
                        return $query->where('job_applies.status', $status);
                    }
                })
                ->orderBy('job_applies.job_apply_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $applications,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Education.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
