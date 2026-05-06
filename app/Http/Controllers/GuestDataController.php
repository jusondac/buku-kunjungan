<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GuestDataController extends Controller
{
    /**
     * Display list of all guests with pagination and search
     * Sorted by longest duration (waiting time) at top
     */
    public function index(Request $request)
    {
        $query = Guest::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Get all guests and add duration data, then sort by duration descending
        $allGuests = $query->get();
        
        // Add duration info to each guest
        $allGuests = $allGuests->map(function ($guest) {
            $guest->duration_info = $this->calculateDuration($guest);
            return $guest;
        });

        // Sort by duration descending (longest first)
        $allGuests = $allGuests->sortByDesc(function ($guest) {
            return $guest->duration_info['seconds'];
        })->values();

        // Manual pagination since we sorted in PHP
        $page = $request->get('page', 1);
        $perPage = 15;
        $guests = new \Illuminate\Pagination\Paginator(
            $allGuests->slice(($page - 1) * $perPage, $perPage)->values(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        $statistics = [
            'total' => Guest::count(),
            'menunggu' => Guest::where('status', 'menunggu')->count(),
            'dilayani' => Guest::where('status', 'dilayani')->count(),
            'selesai' => Guest::where('status', 'selesai')->count(),
        ];

        return view('guests.index', compact('guests', 'statistics'));
    }

    /**
     * Calculate duration for a guest
     * Returns array with formatted time and seconds for sorting
     */
    private function calculateDuration($guest)
    {
        if ($guest->status === 'selesai') {
            // Fixed duration: updated_at - created_at
            $seconds = abs($guest->updated_at->diffInSeconds($guest->created_at));
        } else {
            // Running duration: NOW - created_at
            $seconds = abs(now()->diffInSeconds($guest->created_at));
        }

        // Format duration as mm:ss or HH:mm:ss
        $formatted = $this->formatDurationTime($seconds);

        return [
            'seconds' => $seconds,
            'formatted' => $formatted,
            'is_long' => $seconds > 1800, // More than 30 minutes
        ];
    }

    /**
     * Format duration in seconds to mm:ss or HH:mm:ss format
     */
    private function formatDurationTime($seconds)
    {
        if ($seconds < 0) {
            $seconds = 0;
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

