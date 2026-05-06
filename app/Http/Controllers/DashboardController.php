<?php

namespace App\Http\Controllers;

use App\Models\Guest;

class DashboardController extends Controller
{
    /**
     * Display dashboard with metrics
     */
    public function index()
    {
        $statistics = [
            'total' => Guest::count(),
            'menunggu' => Guest::where('status', 'menunggu')->count(),
            'dilayani' => Guest::where('status', 'dilayani')->count(),
            'selesai' => Guest::where('status', 'selesai')->count(),
        ];

        // Get data for this month
        $thisMonth = [
            'total' => Guest::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'menunggu' => Guest::where('status', 'menunggu')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'dilayani' => Guest::where('status', 'dilayani')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'selesai' => Guest::where('status', 'selesai')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('dashboard.dashboard', compact('statistics', 'thisMonth'));
    }
}
