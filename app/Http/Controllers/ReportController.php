<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Show report page with date range filter
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Guest::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        $guests = $query->latest('created_at')->get();

        $statistics = [
            'total' => $guests->count(),
            'menunggu' => $guests->where('status', 'menunggu')->count(),
            'dilayani' => $guests->where('status', 'dilayani')->count(),
            'selesai' => $guests->where('status', 'selesai')->count(),
        ];

        return view('reports.index', compact('guests', 'statistics', 'startDate', 'endDate'));
    }

    /**
     * Export data to CSV
     */
    public function exportCsv(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Guest::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        $guests = $query->latest('created_at')->get();

        $filename = 'buku_kunjungan_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $handle = fopen('php://output', 'w');

        // Set header
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Write column headers
        fputcsv($handle, [
            'ID',
            'Nama',
            'Telepon',
            'Alamat',
            'Keperluan',
            'Status',
            'Tanggal',
        ], ';');

        // Write data
        foreach ($guests as $guest) {
            fputcsv($handle, [
                $guest->id,
                $guest->name,
                $guest->phone,
                $guest->address,
                $guest->purpose,
                $this->translateStatus($guest->status),
                $guest->created_at->format('d-m-Y H:i:s'),
            ], ';');
        }

        fclose($handle);
        exit;
    }

    /**
     * Export data to Excel
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Guest::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        $guests = $query->latest('created_at')->get();

        $filename = 'buku_kunjungan_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $handle = fopen('php://output', 'w');

        // Set header for Excel compatibility
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // BOM for UTF-8
        fwrite($handle, "\xEF\xBB\xBF");

        // Write column headers
        fputcsv($handle, [
            'ID',
            'Nama',
            'Telepon',
            'Alamat',
            'Keperluan',
            'Status',
            'Tanggal',
        ]);

        // Write data
        foreach ($guests as $guest) {
            fputcsv($handle, [
                $guest->id,
                $guest->name,
                $guest->phone,
                $guest->address,
                $guest->purpose,
                $this->translateStatus($guest->status),
                $guest->created_at->format('d-m-Y H:i:s'),
            ]);
        }

        fclose($handle);
        exit;
    }

    /**
     * Translate status to Indonesian
     */
    private function translateStatus($status)
    {
        $translations = [
            'menunggu' => 'Menunggu',
            'dilayani' => 'Dilayani',
            'selesai' => 'Selesai',
        ];

        return $translations[$status] ?? $status;
    }
}
