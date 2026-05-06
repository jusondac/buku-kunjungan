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
        $timeFilter = $request->input('time_filter', 'hari_ini');

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
        dd(Guest::all());
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

        // Calculate timer-based metrics (only for selesai records)
        $selesaiRecords = $baseQuery->where('status', 'selesai')->get();
        
        $timerMetrics = $this->calculateTimerMetrics($selesaiRecords);

        // Calculate new service metrics
        $serviceMetrics = $this->calculateServiceMetrics($statistics, $selesaiRecords);

        // Calculate total service time (all records - both selesai and ongoing)
        $allRecords = $baseQuery->get();
        $totalServiceTime = $this->calculateTotalServiceTime($allRecords);

        return view('dashboard.dashboard', compact('statistics', 'thisMonth', 'timeFilter', 'timerMetrics', 'serviceMetrics', 'totalServiceTime'));
    }

    /**
     * Calculate service-related metrics for dashboard
     */
    private function calculateServiceMetrics($statistics, $selesaiRecords)
    {
        // A. Average service time (rata-rata waktu layanan)
        $averageSeconds = 0;
        if ($selesaiRecords->count() > 0) {
            $totalSeconds = $selesaiRecords->sum(function ($record) {
                return abs($record->updated_at->diffInSeconds($record->created_at));
            });
            $averageSeconds = $totalSeconds / $selesaiRecords->count();
        }

        // B. Total not completed (belum selesai)
        $totalNotCompleted = $statistics['menunggu'] + $statistics['dilayani'];

        return [
            'average_service_time' => $this->formatDurationSeconds($averageSeconds),
            'average_service_time_raw' => $averageSeconds,
            'total_not_completed' => $totalNotCompleted,
            'total_completed' => $statistics['selesai'],
        ];
    }

    /**
     * Format duration in seconds to mm:ss or HH:mm:ss format
     */
    private function formatDurationSeconds($seconds)
    {
        if ($seconds <= 0) {
            return '-';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        } else {
            return sprintf('%02d:%02d', $minutes, $secs);
        }
    }

    /**
     * Calculate timer-based metrics for service duration
     */
    private function calculateTimerMetrics($selesaiRecords)
    {
        $totalDurationMinutes = 0;
        $minDurationMinutes = null;
        $maxDurationMinutes = null;
        $count = 0;

        foreach ($selesaiRecords as $record) {
            $durationMinutes = $record->duration_minutes ?? 0;
            
            if ($durationMinutes > 0) {
                $totalDurationMinutes += $durationMinutes;
                $count++;
                
                if ($minDurationMinutes === null || $durationMinutes < $minDurationMinutes) {
                    $minDurationMinutes = $durationMinutes;
                }
                
                if ($maxDurationMinutes === null || $durationMinutes > $maxDurationMinutes) {
                    $maxDurationMinutes = $durationMinutes;
                }
            }
        }

        $averageDurationMinutes = $count > 0 ? round($totalDurationMinutes / $count, 1) : 0;

        return [
            'total_duration' => $this->formatDuration($totalDurationMinutes),
            'total_duration_raw' => $totalDurationMinutes,
            'average_duration' => $this->formatDuration($averageDurationMinutes),
            'average_duration_raw' => $averageDurationMinutes,
            'fastest_duration' => $this->formatDuration($minDurationMinutes),
            'fastest_duration_raw' => $minDurationMinutes,
            'slowest_duration' => $this->formatDuration($maxDurationMinutes),
            'slowest_duration_raw' => $maxDurationMinutes,
            'completed_count' => $count,
        ];
    }

    /**
     * Format duration in minutes/hours
     */
    private function formatDuration($minutes)
    {
        if ($minutes === null || $minutes === 0) {
            return '-';
        }

        if ($minutes >= 60) {
            $hours = floor($minutes / 60);
            $mins = $minutes % 60;
            return $mins > 0 ? "{$hours}h {$mins}m" : "{$hours}h";
        }

        return "{$minutes}m";
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

    /**
     * Calculate total service time for all records
     * Includes both selesai (fixed) and ongoing (live) durations
     */
    private function calculateTotalServiceTime($allRecords)
    {
        $totalSeconds = 0;

        foreach ($allRecords as $record) {
            if ($record->status === 'selesai') {
                // Fixed duration
                $seconds = abs($record->updated_at->diffInSeconds($record->created_at));
            } else {
                // Running duration
                $seconds = abs(now()->diffInSeconds($record->created_at));
            }
            $totalSeconds += $seconds;
        }

        return $this->formatDurationDHM($totalSeconds);
    }

    /**
     * Format duration in seconds to D H M format
     * Example: 2D 5H 30M
     */
    private function formatDurationDHM($seconds)
    {
        if ($seconds <= 0) {
            return '0D 0H 0M';
        }

        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        return "{$days}D {$hours}H {$minutes}M";
    }
}

