<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserWin;
use App\Models\Prize;

// Public API routes (no authentication required)
Route::post('/store-user', function (Request $request) {
    try {
        Log::info('Store user request:', $request->except(['resume']));

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'number' => 'required|string|max:20',
            'interested' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'resumeFileName' => 'nullable|string',
        ]);

        unset($validated['resumeFileName']);
        $validated['password'] = bcrypt(Str::random(16));

        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
            $validated['resume_file_name'] = basename($resumePath);
        }

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => $user,
            'id' => $user->id
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Store user error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
});

// Record Win
Route::post('/record-win', function (Request $request) {
    try {
        Log::info('Record win request:', $request->all());

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'prize_id' => 'required|exists:prizes,id',
        ]);

        // Get user and prize data
        $user = User::findOrFail($validated['user_id']);
        $prize = Prize::findOrFail($validated['prize_id']);

        // Create the win record with denormalized data
        $win = UserWin::create([
            'user_id' => $user->id,
            'prize_id' => $prize->id,
            // Denormalized user data
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_number' => $user->number,
            'user_interested' => $user->interested,
            'user_address' => $user->address,
            'user_resume_file_name' => $user->resume_file_name,
            // Denormalized prize data
            'prize_label' => $prize->label,
            'prize_weight' => $prize->weight,
            'prize_price' => $prize->price,
            'prize_active' => $prize->active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Win recorded successfully',
            'win' => $win
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Record win error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
});

// Get Prizes for the wheel
Route::get('/prizes', function () {
    try {
        $prizes = Prize::where('active', true)->get();
        
        return response()->json([
            'success' => true,
            'prizes' => $prizes
        ], 200);

    } catch (\Exception $e) {
        Log::error('Get prizes error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
});

// Protected API routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
