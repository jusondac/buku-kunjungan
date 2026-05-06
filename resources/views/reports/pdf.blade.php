<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header .date-info {
            font-size: 12px;
            margin-top: 5px;
            color: #666;
        }
        .statistics {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-box {
            flex: 1;
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            background-color: #f9f9f9;
        }
        .stat-box .label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-box .value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .filter-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            font-size: 12px;
            border-left: 3px solid #0066cc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #0066cc;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status {
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }
        .status-menunggu {
            background-color: #ffd700;
            color: #333;
        }
        .status-dilayani {
            background-color: #87ceeb;
            color: #333;
        }
        .status-selesai {
            background-color: #90ee90;
            color: #333;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="date-info">
            Dihasilkan: {{ $date_generated }}
        </div>
    </div>

    @if($start_date && $end_date)
        <div class="filter-info">
            <strong>Periode Laporan:</strong> {{ \Carbon\Carbon::parse($start_date)->format('d-m-Y') }} hingga {{ \Carbon\Carbon::parse($end_date)->format('d-m-Y') }}
        </div>
    @endif

    <div class="statistics">
        <div class="stat-box">
            <div class="label">Total Tamu</div>
            <div class="value">{{ $statistics['total'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Menunggu</div>
            <div class="value">{{ $statistics['menunggu'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Dilayani</div>
            <div class="value">{{ $statistics['dilayani'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Selesai</div>
            <div class="value">{{ $statistics['selesai'] }}</div>
        </div>
    </div>

    @if($guests->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Nama</th>
                    <th style="width: 12%;">Telepon</th>
                    <th style="width: 18%;">Alamat</th>
                    <th style="width: 30%;">Keperluan</th>
                    <th style="width: 20%;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($guests as $index => $guest)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $guest->name }}</td>
                        <td>{{ $guest->phone }}</td>
                        <td>{{ substr($guest->address, 0, 40) }}{{ strlen($guest->address) > 40 ? '...' : '' }}</td>
                        <td>{{ $guest->purpose }}</td>
                        <td>{{ $guest->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            Tidak ada data untuk ditampilkan
        </div>
    @endif

    <div class="footer">
        Laporan ini dicetak secara otomatis oleh sistem Buku Kunjungan
    </div>
</body>
</html>
