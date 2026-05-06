<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard with guest list
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

        $guests = $query->latest('created_at')->paginate(10);

        $statistics = [
            'total' => Guest::count(),
            'menunggu' => Guest::where('status', 'menunggu')->count(),
            'dilayani' => Guest::where('status', 'dilayani')->count(),
            'selesai' => Guest::where('status', 'selesai')->count(),
        ];

        return view('dashboard.index', compact('guests', 'statistics'));
    }

    /**
     * Update guest status
     */
    public function updateStatus(Request $request, Guest $guest)
    {
        $request->validate([
            'status' => 'required|in:menunggu,dilayani,selesai',
        ], [
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        $guest->update(['status' => $request->input('status')]);

        return redirect()->back()->with('success', 'Status tamu berhasil diperbarui.');
    }

    /**
     * Delete guest record
     */
    public function destroy(Guest $guest)
    {
        $guest->delete();
        return redirect()->back()->with('success', 'Data tamu berhasil dihapus.');
    }
}
