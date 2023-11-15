<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        $user->projects()->delete();
        $user->tasks()->delete();

        $user->delete();

        return response()->json(['message' => 'Account and associated data deleted.']);
    }
}
