<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobSeekerProfileController;
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
    Route::post('login', 'login');          // Public route for login
    Route::post('register', 'register');    // Public route for registration
    Route::post('logout', 'logout')->middleware('auth:api');  // Protected route for logout
    Route::post('refresh', 'refresh');      // Public route for token refresh
    Route::get('userProfile', 'userProfile')->middleware('auth:api'); // Protected route for profile
});



Route::middleware('auth:api')->group(function(){
    Route::get('/user',[UserController::class,'index']);
    Route::get('/getJobSeekerProfile',[JobSeekerProfileController::class,'getJobSeeker']);
});
