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
     * Show report page with date range and keperluan filter
     */
    public function index(Request $request)
    {
        $dateFilter = $request->input('date_filter', '');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $keperluan = $request->input('keperluan');

        $query = Guest::query();

        // Date filter (preset options)
        if ($dateFilter) {
            $dateRange = $this->getDateRange($dateFilter);
            if ($dateRange[0] && $dateRange[1]) {
                $query->whereBetween('created_at', $dateRange);
            }
        }
        // Custom date range
        elseif ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        if ($keperluan) {
            $query->where('purpose', $keperluan);
        }

        $query->where('status', 'selesai');

        $guests = $query->latest('created_at')->get();

        $kperluanOptions = ['rehabilitas', 'skhpn', 'bagian umum', 'pemberantasan', 'lainnya'];

        return view('reports.index', compact('guests', 'startDate', 'endDate', 'keperluan', 'kperluanOptions', 'dateFilter'));
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
        $keperluan = $request->input('keperluan');

        $filename = 'buku_kunjungan_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

        return Excel::download(
            new GuestsExport($startDate, $endDate, $keperluan),
            $filename
        );
    }

    /**
     * Export data to PDF
     */
    public function exportPdf(Request $request)
    {
        $dateFilter = $request->input('date_filter', '');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $keperluan = $request->input('keperluan');
        $wibNow = now()->setTimezone('Asia/Jakarta');

        $query = Guest::query();

        // Date filter (preset options)
        if ($dateFilter) {
            $dateRange = $this->getDateRange($dateFilter);
            if ($dateRange[0] && $dateRange[1]) {
                $query->whereBetween('created_at', $dateRange);
            }
        }
        // Custom date range
        elseif ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        if ($keperluan) {
            $query->where('purpose', $keperluan);
        }

        $query->where('status', 'selesai');

        $guests = $query->latest('created_at')->get()->map(function ($guest) {
            $guest->created_at = $guest->created_at->copy()->setTimezone('Asia/Jakarta');
            return $guest;
        });

        $data = [
            'title' => 'Laporan SITADIGI',
            'date_generated' => $wibNow->format('d-m-Y H:i:s'),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'guests' => $guests,
        ];

        $pdf = Pdf::loadView('reports.pdf', $data);
        return $pdf->download('buku_kunjungan_' . $wibNow->format('Y_m_d_H_i_s') . '.pdf');
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
            default => [null, null],
        };
    }
}
