<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display dashboard with metrics and time-based filtering
     */
    public function index(Request $request)
    {
        $timeFilter = $request->input('time_filter', 'bulan_ini');

        // Determine date range based on filter
        $dateRange = $this->getDateRange($timeFilter);

        // Build query with date filter
        $baseQuery = Guest::whereBetween('created_at', $dateRange);

        $statistics = [
            'total' => $baseQuery->count(),
            'menunggu' => $baseQuery->where('status', 'menunggu')->count(),
            'dilayani' => $baseQuery->where('status', 'dilayani')->count(),
            'selesai' => $baseQuery->where('status', 'selesai')->count(),
        ];

        // Get data for this month (for secondary reference)
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

        return view('dashboard.dashboard', compact('statistics', 'thisMonth', 'timeFilter'));
    }

    /**
     * Get date range based on time filter
     */
    private function getDateRange($filter)
    {
        $now = now();

        return match($filter) {
            'hari_ini' => [
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay(),
            ],
            'kemarin' => [
                $now->copy()->subDay()->startOfDay(),
                $now->copy()->subDay()->endOfDay(),
            ],
            'seminggu_terakhir' => [
                $now->copy()->subDays(7)->startOfDay(),
                $now->copy()->endOfDay(),
            ],
            'sebulan_terakhir' => [
                $now->copy()->subMonth()->startOfDay(),
                $now->copy()->endOfDay(),
            ],
            'setahun_terakhir' => [
                $now->copy()->subYear()->startOfDay(),
                $now->copy()->endOfDay(),
            ],
            default => [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
            ], // bulan_ini
        };
    }
}

