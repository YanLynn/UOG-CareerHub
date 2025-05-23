<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EmployerProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobSeekerProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth:api');
    Route::post('refresh', 'refresh');
    Route::get('userProfile', 'userProfile')->middleware('auth:api');
});



Route::middleware('auth:api')->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/getJobSeekerProfile', [JobSeekerProfileController::class, 'getJobSeeker']);
    Route::post('/updateProfile', [JobSeekerProfileController::class, 'updateProfile']);
    Route::post('/updateSkill', [JobSeekerProfileController::class, 'updateSkill']);
    Route::post('/uploadResumeFile', [JobSeekerProfileController::class, 'uploadResumeFile']);
    Route::post('/addWorkExp', [JobSeekerProfileController::class, 'addWorkExp']);
    Route::post('/deleteHistory', [JobSeekerProfileController::class, 'deleteCareerHistory']);
    Route::post('/saveEdu', [JobSeekerProfileController::class, 'saveEducation']);
    Route::post('/deleteEducation/{id}', [JobSeekerProfileController::class, 'deleteEducation']);
    Route::get('/jobSeekerJobList/{status}', [JobSeekerProfileController::class, 'jobSeekerJobList']);


    Route::post('/changeEmail', [UserController::class, 'changeEmail']);
    Route::post('/changePassword', [UserController::class, 'changePassword']);

    Route::post('/jobApplyAndSave', [JobController::class, 'jobApplyAndSave']);


    //employer
    Route::get('/getEmployerProfile', [EmployerProfileController::class, 'getEmployerProfile']);
    Route::post('/createJobPost', [EmployerProfileController::class, 'createJobPost']);
    Route::post('/deleteJob', [EmployerProfileController::class, 'deleteJob']);

    Route::get('/getJobById/{id}', [EmployerProfileController::class, 'getJobById']);
    Route::post('/updateJob', [EmployerProfileController::class, 'updateJob']);
    Route::get('/getApplicationsByJob', [EmployerProfileController::class, 'getApplicationsByJob']);
    Route::post('/updateJobApplicationStatus', [EmployerProfileController::class, 'updateJobApplicationStatus']);



    Route::post('/contacts', [ChatController::class, 'chatContacts']);
    Route::get('/messages/{chatRoomId}', [ChatController::class, 'getMessages']);
    Route::post('/messages/{chatRoomId}', [ChatController::class, 'sendMessage']);

    Route::post('/chat/typing', [ChatController::class, 'userTyping']);
});

Route::get('/getCompany', [HomeController::class, 'getCompany']);
Route::get('/countries', [SearchController::class, 'searchCountry']);

Route::get('/getJobsList', [JobController::class, 'getJobsList']);
Route::get('/getCountry', [SearchController::class, 'getCountry']);
Route::get('/getCategory', [SearchController::class, 'getCategory']);

Route::get('/checkJobApplied', [JobController::class, 'checkJobApplied']);




