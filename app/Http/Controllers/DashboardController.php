<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

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

        if ($request->filled('start_date') || $request->filled('end_date')) {
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
        $purposeCounts = [];
        $purposeRows = (clone $baseQuery)->select('purpose', 'purpose_lainnya')->get();
        foreach ($purposeRows as $row) {
            $label = $row->purpose;
            if ($label === 'lainnya') {
                $label = trim((string) $row->purpose_lainnya);
                if ($label === '') {
                    $label = 'Lainnya';
                }
            }

            $label = Str::title($label);
            $purposeCounts[$label] = ($purposeCounts[$label] ?? 0) + 1;
        }

        arsort($purposeCounts);
        $purposeLabels = array_keys($purposeCounts);
        $purposeValues = array_values($purposeCounts);
        $purposePercentages = [];
        $purposeTotal = max($statistics['total'], 1);
        foreach ($purposeValues as $count) {
            $purposePercentages[] = round(($count / $purposeTotal) * 100, 1);
        }
        $filterDates = [
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ];

        return view('dashboard.dashboard', compact(
            'statistics',
            'filterDates',
            'purposeLabels',
            'purposeValues',
            'purposePercentages',
            'purposeTotal'
        ));
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

