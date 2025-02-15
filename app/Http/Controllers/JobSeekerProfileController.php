<?php

namespace App\Http\Controllers;

use App\Models\JobSeeker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\User;
use App\Services\ImageUploadService;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;

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
            $existingSkillModels = Skill::whereIn(DB::raw('LOWER(name)'),$skills)->get();
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

    public function uploadResumeFile(Request $req){

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
            ['folder' => 'documents',
            'timeout' => 120
            ]
        );

        $fileUrl = $uploadedFile->getSecurePath();

        if($fileUrl){
            JobSeeker::where('user_id',Auth::user()->id)->update([
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
}
