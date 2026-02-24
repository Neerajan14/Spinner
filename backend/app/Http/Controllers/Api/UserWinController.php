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
        return response()->json($wins->map(fn ($win) => $this->formatWinForResponse($win)));
    }

    // Get wins for specific user with all user details
    public function getUserWins($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        $wins = $user->wins()->with('prize')->get()->each(function ($win) use ($user) {
            $win->setRelation('user', $user);
        });

        return response()->json($wins->map(fn ($win) => $this->formatWinForResponse($win)));
    }

    // Create a new win
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'prize_id' => 'required|exists:prizes,id',
        ]);

        $user = User::find($validated['user_id']);
        $prize = Prize::find($validated['prize_id']);

        $win = UserWin::create([
            'user_id'                 => $user->id,
            'prize_id'                => $prize->id,
            'user_name'               => $user->name,
            'user_email'              => $user->email,
            'user_number'             => $user->number,
            'user_interested'         => $user->interested,
            'user_address'            => $user->address,
            'user_resume_file_name'   => $user->resume_file_name,
            'prize_label'             => $prize->label,
            'prize_weight'            => $prize->weight,
            'prize_price'             => $prize->price,
            'prize_active'            => $prize->active,
        ]);

        $win->load(['user', 'prize']);
        return response()->json($this->formatWinForResponse($win), 201);
    }

    // Get specific win
    public function show($id)
    {
        $win = UserWin::with(['user', 'prize'])->find($id);
        if (!$win) {
            return response()->json(['error' => 'Win not found'], 404);
        }
        
        return response()->json($this->formatWinForResponse($win));
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

    /**
     * Format a UserWin model for a consistent API response.
     *
     * @param \App\Models\UserWin $win
     * @return array
     */
    private function formatWinForResponse(UserWin $win): array
    {
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
            ],
        ];
    }
}