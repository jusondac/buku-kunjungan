<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GuestDataController extends Controller
{
    /**
     * Display list of all guests with pagination and search
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

        $guests = $query->latest('created_at')->paginate(15);

        $statistics = [
            'total' => Guest::count(),
            'menunggu' => Guest::where('status', 'menunggu')->count(),
            'dilayani' => Guest::where('status', 'dilayani')->count(),
            'selesai' => Guest::where('status', 'selesai')->count(),
        ];

        return view('guests.index', compact('guests', 'statistics'));
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

