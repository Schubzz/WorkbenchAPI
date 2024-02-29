<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Delete the user account and associated data.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->projects()->delete();
        $user->tasks()->delete();

        $user->delete();

        return response()->json(['message' => 'Account and associated data deleted.']);
    }

    /**
     * Update the user's profile information.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'nullable|unique:users,username,' . auth()->id(),
            'email' => 'nullable|email|unique:users,email,' . auth()->id(),
        ]);

        $existingUsername = User::where('username', $request->input('username'))
            ->where('id', '!=', auth()->id())
            ->exists();

        $existingEmail = User::where('email', $request->input('email'))
            ->where('id', '!=', auth()->id())
            ->exists();

        if ($existingUsername || $existingEmail) {
            return response()->json(['message' => 'Benutzername oder E-Mail-Adresse bereits vergeben.'], 422);
        }

        $user = auth()->user();

        if ($request -> has("username")){
            $user->username = $request->input('username');
        }

        if ($request -> has("email")){
            $user->email = $request->input('email');
        }

        $user->save();


        return response()->json(['message' => 'Profil erfolgreich aktualisiert.']);
    }
}
