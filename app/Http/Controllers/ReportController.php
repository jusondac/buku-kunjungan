<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Exports\GuestsExport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
     * Export data to Excel (.xlsx)
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $filename = 'buku_kunjungan_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

        return Excel::download(
            new GuestsExport($startDate, $endDate),
            $filename
        );
    }

    /**
     * Export data to PDF
     */
    public function exportPdf(Request $request)
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

        $data = [
            'title' => 'Laporan Buku Kunjungan',
            'date_generated' => now()->format('d-m-Y H:i:s'),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'guests' => $guests,
            'statistics' => $statistics,
        ];

        $pdf = Pdf::loadView('reports.pdf', $data);
        return $pdf->download('buku_kunjungan_' . now()->format('Y_m_d_H_i_s') . '.pdf');
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
