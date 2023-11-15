<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => ['required','string', 'email', 'max:255'],
            'password' => ['required', 'string']
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 422);
        }

        $request->session()->regenerate();

       $user = User::where('email', $request->input('email'))->firstOrFail();

        return response()->json([
            'message' => 'You are now logged in',
            'user' => $user
        ]);
    }
}
