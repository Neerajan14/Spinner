<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prize;
use App\Models\Spin;
use Illuminate\Http\Request;

class SpinController extends Controller
{
    // 1️⃣ Fetch all active prizes
    public function prizes()
    {
        return Prize::where('active', true)->get();
    }

    // 2️⃣ Store a spin result (random or fixed)
    public function spin(Request $request)
    {
        $prizes = Prize::where('active', true)->get();

        // Pick a random prize if you want server-controlled
        $winner = $prizes->random();

        // Store the spin in DB
        $spin = Spin::create([
            'prize_id' => $winner->id,
            'user_id'  => $request->user_id, 
            'user_ip' => $request->ip()
        ]);

        // Return winner info
        return response()->json($winner);
    }

    // 3️⃣ Optional: Store spin from frontend (frontend picks winner)
    public function store(Request $request)
    {
        $prize = Prize::findOrFail($request->prize_id);

        $spin = Spin::create([
            'prize_id' => $prize->id,
            'user_ip' => $request->ip()
        ]);

        return response()->json([
            'message' => 'Spin stored successfully',
            'prize' => $prize
        ]);
    }
}