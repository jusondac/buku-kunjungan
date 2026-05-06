<?php

namespace App\Exports;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GuestsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query(): Builder
    {
        $query = Guest::query();

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Telepon',
            'Alamat',
            'Keperluan',
            'Status',
            'Tanggal',
        ];
    }

    public function map($guest): array
    {
        static $count = 0;
        $count++;

        $statusMap = [
            'menunggu' => 'Menunggu',
            'dilayani' => 'Dilayani',
            'selesai' => 'Selesai',
        ];

        return [
            $count,
            $guest->name,
            $guest->phone,
            $guest->address,
            $guest->purpose,
            $statusMap[$guest->status] ?? $guest->status,
            $guest->created_at->format('d-m-Y H:i'),
        ];
    }
}
