<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\DB;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        $totalUsers = \App\Models\User::count();
        
        try {
            $totalWins = DB::table('user_wins')->count();
            $totalPrizeValue = DB::table('user_wins')
                ->join('prizes', 'user_wins.prize_id', '=', 'prizes.id')
                ->sum('prizes.price');
        } catch (\Exception $e) {
            $totalWins = 0;
            $totalPrizeValue = 0;
        }
        
        return view('dashboard', compact('totalUsers', 'totalWins', 'totalPrizeValue'));
    })->name('dashboard');
    
    // Users List
    Route::get('/users', function () {
        $users = \App\Models\User::paginate(15);
        return view('users', compact('users'));
    })->name('users');
    
    // User Wins
    Route::get('/user-wins', function () {
        try {
            $userWins = DB::table('user_wins')
                ->join('users', 'user_wins.user_id', '=', 'users.id')
                ->join('prizes', 'user_wins.prize_id', '=', 'prizes.id')
                ->select(
                    'user_wins.id',
                    'user_wins.created_at',
                    'users.name as user_name',
                    'users.email as user_email',
                    'users.number as user_number',
                    'users.interested as user_interested',
                    'users.address as user_address',
                    'users.resume_file_name as user_resume_file_name',
                    'prizes.label as prize_label',
                    'prizes.price as prize_price'
                )
                ->paginate(15);
        } catch (\Exception $e) {
            $userWins = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
        }
        
        return view('user-wins', compact('userWins'));
    })->name('user-wins');
    
    // Export routes
    Route::get('/users/export', function () {
        return response()->download(storage_path('app/users.csv'));
    })->name('users.export');
    
    Route::get('/user-wins/export', function () {
        return response()->download(storage_path('app/user-wins.csv'));
    })->name('user-wins.export');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Root redirect - FIXED
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

// Export Users to CSV
Route::get('/users/export', function () {
    $users = \App\Models\User::all();
    
    $filename = 'users_' . date('Y-m-d_His') . '.csv';
    $filepath = storage_path('app/' . $filename);
    
    $file = fopen($filepath, 'w');
    
    // Add CSV headers
    fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Interested In', 'Address', 'Joined Date']);
    
    // Add data rows
    foreach ($users as $user) {
        fputcsv($file, [
            $user->id,
            $user->name,
            $user->email,
            $user->number,
            $user->interested,
            $user->address ?? 'N/A',
            $user->created_at->format('Y-m-d H:i:s')
        ]);
    }
    
    fclose($file);
    
    return response()->download($filepath)->deleteFileAfterSend(true);
})->name('users.export');

// Export User Wins to CSV
Route::get('/user-wins/export', function () {
    try {
        $userWins = DB::table('user_wins')
            ->join('users', 'user_wins.user_id', '=', 'users.id')
            ->join('prizes', 'user_wins.prize_id', '=', 'prizes.id')
            ->select(
                'user_wins.id',
                'prizes.label as prize_label',
                'prizes.price as prize_price',
                'users.name as user_name',
                'users.number as user_number',
                'users.email as user_email',
                'users.interested as user_interested',
                'users.address as user_address',
                'users.resume_file_name as user_resume_file_name',
                'user_wins.created_at'
            )
            ->get();
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'No data to export');
    }
    
    $filename = 'user_wins_' . date('Y-m-d_His') . '.csv';
    $filepath = storage_path('app/' . $filename);
    
    $file = fopen($filepath, 'w');
    
    // Add CSV headers
    fputcsv($file, [
        'ID', 
        'Prize Won', 
        'Prize Value', 
        'Full Name', 
        'Phone Number', 
        'Email Address', 
        'Interested In', 
        'Address', 
        'Has Resume',
        'Won At'
    ]);
    
    // Add data rows
    foreach ($userWins as $win) {
        fputcsv($file, [
            $win->id,
            $win->prize_label,
            $win->prize_price,
            $win->user_name,
            $win->user_number,
            $win->user_email,
            $win->user_interested,
            $win->user_address ?? 'N/A',
            $win->user_resume_file_name ? 'Yes' : 'No',
            \Carbon\Carbon::parse($win->created_at)->format('M d, Y H:i')
        ]);
    }
    
    fclose($file);
    
    return response()->download($filepath)->deleteFileAfterSend(true);
})->name('user-wins.export');