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
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');
        $periode = $request->input('periode', 'hari_ini');

        if ($startDateInput || $endDateInput) {
            if ($startDateInput && $endDateInput) {
                $startDate = Carbon::parse($startDateInput)->startOfDay();
                $endDate = Carbon::parse($endDateInput)->endOfDay();
            } elseif ($startDateInput) {
                $startDate = Carbon::parse($startDateInput)->startOfDay();
                $endDate = Carbon::parse($startDateInput)->endOfDay();
            } else {
                $startDate = Carbon::parse($endDateInput)->startOfDay();
                $endDate = Carbon::parse($endDateInput)->endOfDay();
            }
        } else {
            [$startDate, $endDate] = $this->resolvePeriodRange($periode);
        }

        $baseQuery = Guest::whereBetween('created_at', [$startDate, $endDate]);

        $statistics = [
            'total' => (clone $baseQuery)->count(),
            'menunggu' => (clone $baseQuery)->where('status', 'menunggu')->count(),
            'dilayani' => (clone $baseQuery)->where('status', 'dilayani')->count(),
            'selesai' => (clone $baseQuery)->where('status', 'selesai')->count(),
        ];
        $filterDates = [
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ];

        return view('dashboard.dashboard', compact('statistics', 'filterDates'));
    }

    /**
     * Resolve period filter into start/end range
     */
    private function resolvePeriodRange(string $periode)
    {
        $now = Carbon::now();

        switch ($periode) {
            case 'seminggu':
                return [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()];
            case 'sebulan':
                return [$now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay()];
            case 'setahun':
                return [$now->copy()->subDays(364)->startOfDay(), $now->copy()->endOfDay()];
            case 'hari_ini':
            default:
                return [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
        }
    }

}

