@extends('dashboard-layout')

@section('page_title', 'Export Data')

@section('content')
<div class="space-y-6">
    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Laporan</h3>
        <form method="GET" action="{{ route('reports.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keperluan</label>
                    <select name="keperluan"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Keperluan</option>
                        @foreach($kperluanOptions as $option)
                            <option value="{{ $option }}" {{ request('keperluan') === $option ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('-', ' ', $option)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Tampilkan
                    </button>
                    <a href="{{ route('reports.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition text-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistics -->
    @if(request('start_date') || request('end_date') || request('keperluan'))
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
                <p class="text-gray-600 text-sm font-medium mb-2">Total Tamu</p>
                <p class="text-3xl font-bold text-blue-600">{{ $statistics['total'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-yellow-500">
                <p class="text-gray-600 text-sm font-medium mb-2">Menunggu</p>
                <p class="text-3xl font-bold text-yellow-500">{{ $statistics['menunggu'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
                <p class="text-gray-600 text-sm font-medium mb-2">Dilayani</p>
                <p class="text-3xl font-bold text-blue-500">{{ $statistics['dilayani'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-green-600">
                <p class="text-gray-600 text-sm font-medium mb-2">Selesai</p>
                <p class="text-3xl font-bold text-green-600">{{ $statistics['selesai'] }}</p>
            </div>
        </div>

        <!-- Export Buttons -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Export Data</h3>
            <div class="flex gap-4">
                <a href="{{ route('reports.export.excel', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'keperluan' => request('keperluan')]) }}" 
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition text-center">
                    📊 Export Excel (.xlsx)
                </a>
                <a href="{{ route('reports.export.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'keperluan' => request('keperluan')]) }}" 
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition text-center">
                    📄 Export PDF
                </a>
            </div>
        </div>

        <!-- Data Preview Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-gray-100 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Preview Data ({{ $guests->count() }} tamu)</h3>
            </div>
            @if($guests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Telepon</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Keperluan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($guests->take(20) as $index => $guest)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $guest->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $guest->phone }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($guest->purpose, 30) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($guest->status === 'menunggu')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Menunggu</span>
                                        @elseif($guest->status === 'dilayani')
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Dilayani</span>
                                        @else
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $guest->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($guests->count() > 20)
                    <div class="px-6 py-4 bg-gray-50 border-t text-sm text-gray-600">
                        Menampilkan 20 dari {{ $guests->count() }} data. Download untuk melihat semua data.
                    </div>
                @endif
            @else
                <div class="px-6 py-12 text-center">
                    <p class="text-gray-500 text-lg">Tidak ada data sesuai filter</p>
                </div>
            @endif
        </div>
    @else
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
            <p class="text-blue-800 text-lg">Pilih filter dan klik "Tampilkan" untuk melihat data dan export</p>
        </div>
    @endif
</div>
@endsection
