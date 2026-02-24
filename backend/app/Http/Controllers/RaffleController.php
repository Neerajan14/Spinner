<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Prize;
use App\Models\UserWin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RaffleController extends Controller
{
    public function getUserWins($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $wins = $user->wins()->with('prize')->get();
        return response()->json($wins);
    }
    
    public function getUserPrizes($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $prizes = $user->prizes;
        return response()->json($prizes);
    }
    
    public function getPrizeWinners($prizeId)
    {
        $prize = Prize::find($prizeId);
        if (!$prize) {
            return response()->json(['error' => 'Prize not found'], 404);
        }
        $winners = $prize->wins()->with('user')->get();
        return response()->json($winners);
    }

    public function recordWin(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'prize_id' => 'required|exists:prizes,id',
        ]);

        $win = UserWin::create($validated);
        return response()->json(['success' => true, 'data' => $win], 201);
    }
}