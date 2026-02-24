<?php

namespace App\Http\Controllers\Api;

use App\Models\UserWin;
use App\Models\User;
use App\Models\Prize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserWinController extends Controller
{
    // Get all user wins with user and prize details
    public function index()
    {
        $wins = UserWin::with(['user', 'prize'])->get();
        
        $formattedWins = $wins->map(function($win) {
            return [
                'prize' => [
                    'id' => $win->prize->id,
                    'label' => $win->prize->label,
                    'weight' => $win->prize->weight,
                    'price' => $win->prize->price,
                    'active' => $win->prize->active,
                ],
                'user' => [
                    'id' => $win->user->id,
                    'name' => $win->user->name,
                    'number' => $win->user->number,
                    'email' => $win->user->email,
                    'interested' => $win->user->interested,
                    'address' => $win->user->address,
                    'resume_file_name' => $win->user->resume_file_name,
                ]
            ];
        });
        
        return response()->json($formattedWins);
    }

    // Get wins for specific user with all user details
    public function getUserWins($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        $wins = $user->wins()->with('prize')->get();
        
        $formattedWins = $wins->map(function($win) {
            return [
                'prize' => [
                    'id' => $win->prize->id,
                    'label' => $win->prize->label,
                    'weight' => $win->prize->weight,
                    'price' => $win->prize->price,
                    'active' => $win->prize->active,
                ],
                'user' => [
                    'id' => $win->user->id,
                    'name' => $win->user->name,
                    'number' => $win->user->number,
                    'email' => $win->user->email,
                    'interested' => $win->user->interested,
                    'address' => $win->user->address,
                    'resume_file_name' => $win->user->resume_file_name,
                ]
            ];
        });
        
        return response()->json($formattedWins);
    }

    // Create a new win
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'prize_id' => 'required|exists:prizes,id',
        ]);

        $win = UserWin::create($validated);
        $win->load(['user', 'prize']);
        
        return response()->json([
            'prize' => [
                'id' => $win->prize->id,
                'label' => $win->prize->label,
                'weight' => $win->prize->weight,
                'price' => $win->prize->price,
                'active' => $win->prize->active,
            ],
            'user' => [
                'id' => $win->user->id,
                'name' => $win->user->name,
                'number' => $win->user->number,
                'email' => $win->user->email,
                'interested' => $win->user->interested,
                'address' => $win->user->address,
                'resume_file_name' => $win->user->resume_file_name,
            ]
        ], 201);
    }

    // Get specific win
    public function show($id)
    {
        $win = UserWin::with(['user', 'prize'])->find($id);
        if (!$win) {
            return response()->json(['error' => 'Win not found'], 404);
        }
        
        return response()->json([
            'prize' => [
                'id' => $win->prize->id,
                'label' => $win->prize->label,
                'weight' => $win->prize->weight,
                'price' => $win->prize->price,
                'active' => $win->prize->active,
            ],
            'user' => [
                'id' => $win->user->id,
                'name' => $win->user->name,
                'number' => $win->user->number,
                'email' => $win->user->email,
                'interested' => $win->user->interested,
                'address' => $win->user->address,
                'resume_file_name' => $win->user->resume_file_name,
            ]
        ]);
    }

    // Delete a win
    public function destroy($id)
    {
        $win = UserWin::find($id);
        if (!$win) {
            return response()->json(['error' => 'Win not found'], 404);
        }
        $win->delete();
        return response()->json(['success' => true, 'message' => 'Win deleted']);
    }
}