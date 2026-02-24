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
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $prizes = Prize::where('active', true)->get();

        // Pick a random prize if you want server-controlled
        $winner = $prizes->random();

        $user = \App\Models\User::find($request->user_id);

        // Store the spin in DB
        $spin = Spin::create([
            'prize_id' => $winner->id,
            'user_id'  => $user->id,
            'user_ip' => $request->ip(),
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_number' => $user->number,
            'user_address' => $user->address,
        ]);

        // Return winner info
        return response()->json($winner);
    }

    // 3️⃣ Optional: Store spin from frontend (frontend picks winner)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prize_id' => 'required|exists:prizes,id',
            'user_name' => 'nullable|string|max:255',
            'user_email' => 'nullable|email|max:255',
            'user_number' => 'nullable|string|max:255',
            'user_address' => 'nullable|string|max:255',
        ]);

        $spin = Spin::create([
            'prize_id' => $validated['prize_id'],
            'user_ip' => $request->ip(),
            'user_name' => $validated['user_name'] ?? null,
            'user_email' => $validated['user_email'] ?? null,
            'user_number' => $validated['user_number'] ?? null,
            'user_address' => $validated['user_address'] ?? null,
        ]);

        return response()->json([
            'message' => 'Spin stored successfully',
            'prize' => Prize::find($validated['prize_id'])
        ]);
    }
}