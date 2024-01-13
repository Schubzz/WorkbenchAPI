<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
//        validate request
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string']
        ]);

//        check if user exists
        $user = User::where('email', $request->input('email'))->first();

//        check if password is correct
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 422);
        }

//        create token
        $request->session()->regenerate();

//        return response if successful
        return response()->json([
            'message' => 'You are now logged in',
            'user' => $user,
        ], 200);
    }
}
