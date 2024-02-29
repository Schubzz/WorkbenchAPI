<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function uploadProfileImage(Request $request)
    {

        $request->validate([
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = $user->id . '_profile.jpg';

            Storage::disk('public')->put('images/profile/' . $imageName, file_get_contents($image));

            $user->profile_image = 'images/profile/' . $imageName;
            $user->save();

            return response()->json([
                'message' => 'Profile image uploaded successfully.',
                'profile_img' => $user->profile_image,
            ]);
        }

        return response()->json(['message' => 'No profile image provided.'], 422);
    }

    public function getProfileImage(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $imagePath = asset('/storage/' . $user->profile_image);

        return response()->json([
            'image_path' => $imagePath,
        ]);
    }


    public function deleteProfileImage(Request $request)
    {
        $user = $request->user();

        if ($user->profile_image) {
            Storage::delete('public/images/profile/' . $user->id . '_profile.jpg');
            $user->profile_image = 'default.jpg';
            $user->save();
        }

        return response()->json(['message' => 'Profile image deleted successfully.']);
    }
}

