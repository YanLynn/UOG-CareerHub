<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getAllUser = User::All();
        return response()->json([
            'user' => $getAllUser,
        ], 201);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function changeEmail(Request $req){

        try {
            $req->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            $user = Auth::user();
            $existingUser = User::where('email', $user->email)->first();

            if (!$existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email does not exist in our records. Please enter a valid email.'
                ], 400);
            }

            if (!Hash::check($req->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect password. Please try again.'
                ], 401);
            }

            $existingUser->update(['email' => $req->email]);

            return response()->json([
                'success' => true,
                'message' => 'Email updated successfully!',
                'data' => ['email' => $existingUser->email]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update email.',
                'error' => $th->getMessage()
            ], 500);
        }

    }

    public function changePassword(Request $req){

        try {
            $req->validate([
                'current_password' => 'required',
                'new_password' => 'required'
            ]);

            $user = Auth::user();
            $existingUser = User::where('email', $user->email)->first();
            if (!Hash::check($req->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect password. Please try again.'
                ], 400);
            }

            $existingUser->update(['password' => $req->new_password]);
            return response()->json([
                'success' => true,
                'message' => 'password updated successfully!',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
