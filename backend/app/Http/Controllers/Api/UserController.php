<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $resumeFileName = null;

        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $resumeFileName = $file->getClientOriginalName();
            $file->storeAs('public/resumes', $resumeFileName);
        }

        $user = User::updateOrCreate(
            ['email' => $request->email],
            [
                'name'             => $request->name,
                'number'           => $request->number,
                'interested'       => $request->interested,
                'address'          => $request->address ?? null,
                'resume_file_name' => $resumeFileName,
            ]
        );

        return response()->json([
            'message' => 'User stored successfully',
            'id'      => $user->id
        ]);
    }
}
