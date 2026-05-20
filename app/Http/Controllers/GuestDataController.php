<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GuestDataController extends Controller
{
    /**
     * Display list of all guests with pagination and search
     * Sorted by newest records at top
     */
    public function index(Request $request)
    {
        $query = Guest::query();
        $periode = $request->input('periode', 'hari_ini');

        if ($request->filled('start_date') || $request->filled('end_date')) {
            $startDateInput = $request->input('start_date');
            $endDateInput = $request->input('end_date');

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

        $query->whereBetween('created_at', [$startDate, $endDate]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $guests = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $statisticsQuery = Guest::whereBetween('created_at', [$startDate, $endDate]);
        $statistics = [
            'total' => (clone $statisticsQuery)->count(),
            'menunggu' => (clone $statisticsQuery)->where('status', 'menunggu')->count(),
            'dilayani' => (clone $statisticsQuery)->where('status', 'dilayani')->count(),
            'selesai' => (clone $statisticsQuery)->where('status', 'selesai')->count(),
        ];

        return view('guests.index', compact('guests', 'statistics'));
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

    /**
     * Update guest status
     * Cannot update status if already 'selesai' (read-only)
     */
    public function updateStatus(Request $request, Guest $guest)
    {
        // Check if status is already 'selesai' - prevent updates
        if ($guest->status === 'selesai') {
            return redirect()->back()->with('error', 'Data sudah selesai dan tidak dapat diubah lagi.');
        }

        $request->validate([
            'status' => 'required|in:menunggu,dilayani,selesai',
        ], [
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        $newStatus = $request->input('status');
        
        // If changing to 'selesai', calculate duration and set completed_at
        if ($newStatus === 'selesai') {
            $now = now();
            $guest->update([
                'status' => $newStatus,
                'completed_at' => $now,
                'duration_seconds' => $now->diffInSeconds($guest->created_at),
            ]);
        } else {
            $guest->update(['status' => $newStatus]);
        }

        if ($newStatus === 'selesai') {
            return redirect()->back()
                ->with('success', 'Status berhasil dirubah.')
                ->with('status_popup', 'Status berhasil dirubah.');
        }

        return redirect()->back()->with('success', 'Status tamu berhasil diperbarui.');
    }

    /**
     * Delete guest record
     * Cannot delete if status is 'selesai'
     */
    public function destroy(Guest $guest)
    {
        // Prevent deletion if status is 'selesai'
        if ($guest->status === 'selesai') {
            return redirect()->back()->with('error', 'Data sudah selesai dan tidak dapat dihapus.');
        }

        $guest->delete();
        return redirect()->back()->with('success', 'Data tamu berhasil dihapus.');
    }
}

