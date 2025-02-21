<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\JobSeeker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password',
            ], 401);
        }

        $user = Auth::user();
        if($user){
            User::where('id', $user->id)->update(['last_login' => Carbon::now()]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function register(Request $request)
    {

        $getUser = User::where('email', $request->email)->first();
        if ($getUser == $request->email) {
            return response()->json(['error' => 'The email has already been taken.'], 400);
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
            'userType' => 'required|in:JobSeeker,Employer',
            'companyName' => 'required_if:userType,employer|unique:employees,company_name',
            'companyWebsite' => 'nullable|url',
        ], [
            'email.unique' => 'The email has already been taken.',
            'password.confirmed' => 'Passwords do not match.',
            'companyName.required_if' => 'Company name is required for employers.',
            'companyName.unique' => 'The company name has already been taken.',
        ]);

        // Create the user and handle any other logic
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->last_login = Carbon::now();
        $user->role = $request->userType;
        $user->save();

        if ($request->userType === 'Employer') {
            Employer::create([
                'user_id' => $user->id,
                'company_name' => $request->companyName,
                'company_website' => $request->companyWebsite,

            ]);
        } elseif ($request->userType === 'Jobeeker') {
            JobSeeker::create([
                'user_id' => $user->id,
            ]);
        } else {
            // Handle unexpected userType
            return response()->json(['error' => 'Invalid user type'], 400);
        }

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function logout(Request $request)
    {

        Auth::logout();  // Invalidate the token

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
    public function userProfile()
    {
        if (Auth::attempt()) {
            return response()->json(Auth::user());
        } else {
            return response()->json("user not login");
        }
    }
}
