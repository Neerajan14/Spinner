<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Only HR can access
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:HR']);
    }

    // Get all users
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return response()->json([
            'count' => $users->count(),
            'users' => $users->map(function($user) {
                return [
                    'id'               => $user->id,
                    'name'             => $user->name,
                    'email'            => $user->email,
                    'number'           => $user->number,
                    'interested'       => $user->interested,
                    'address'          => $user->address ?? '-',
                    'resume_file_name' => $user->resume_file_name ?? '-',
                    'resume_url'       => $user->resume_file_name ? asset('storage/resumes/' . $user->resume_file_name) : null,
                    'won_item'         => $user->won_item ?? '-',
                    'created_at'       => $user->created_at->toDateTimeString(),
                    'updated_at'       => $user->updated_at->toDateTimeString(),
                ];
            })
        ]);
    }

    // Store or update user
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'number'     => 'required|string|max:20',
            'interested' => 'required|string|max:255',
            'address'    => 'nullable|string|max:500',
            'resume'     => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $resumeFileName = null;

        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->storeAs('public/resumes', $filename);
            $resumeFileName = $filename;
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
            'user'    => [
                'id'               => $user->id,
                'name'             => $user->name,
                'email'            => $user->email,
                'number'           => $user->number,
                'interested'       => $user->interested,
                'address'          => $user->address ?? '-',
                'resume_file_name' => $user->resume_file_name ?? '-',
                'resume_url'       => $user->resume_file_name ? asset('storage/resumes/' . $user->resume_file_name) : null,
                'won_item'         => $user->won_item ?? '-',
                'created_at'       => $user->created_at->toDateTimeString(),
                'updated_at'       => $user->updated_at->toDateTimeString(),
            ],
        ]);
    }
}