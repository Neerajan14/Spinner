<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserWin;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats only
        $totalUsers = User::count();
        $totalWins = UserWin::count();
        $totalPrizeValue = UserWin::sum('prize_price');
        
        return view('dashboard', compact('totalUsers', 'totalWins', 'totalPrizeValue'));
    }

    public function users()
    {
        $users = User::latest()->paginate(15);
        return view('users', compact('users'));
    }

    public function userWins()
    {
        $userWins = UserWin::with(['user', 'prize'])->latest()->paginate(15);
        return view('user-wins', compact('userWins'));
    }

    // Export Users to CSV (Excel compatible)
    public function exportUsers()
    {
        $users = User::all();
        
        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel to recognize UTF-8 encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Full Name',
                'Phone Number',
                'Email Address',
                'Interested In',
                'Address',
                'Resume File Name',
                'Joined Date'
            ]);
            
            // Add data rows
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->number,
                    $user->email,
                    $user->interested,
                    $user->address ?? 'N/A',
                    $user->resume_file_name ?? 'N/A',
                    $user->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Export User Wins to CSV (Excel compatible)
    public function exportUserWins()
    {
        $userWins = UserWin::all();
        
        $filename = 'user_wins_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($userWins) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel to recognize UTF-8 encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
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
                'Resume File Name',
                'Won Date'
            ]);
            
            // Add data rows
            foreach ($userWins as $win) {
                fputcsv($file, [
                    $win->id,
                    $win->prize_label,
                    number_format($win->prize_price, 2, '.', ''),
                    $win->user_name,
                    $win->user_number,
                    $win->user_email,
                    $win->user_interested,
                    $win->user_address ?? 'N/A',
                    $win->user_resume_file_name ?? 'N/A',
                    $win->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
