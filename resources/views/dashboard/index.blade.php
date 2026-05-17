@extends('layout')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Petugas</h2>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-medium mb-2">Total Tamu</p>
            <p class="text-3xl font-bold text-blue-600">{{ $statistics['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-medium mb-2">Menunggu</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $statistics['menunggu'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-medium mb-2">Dilayani</p>
            <p class="text-3xl font-bold text-blue-600">{{ $statistics['dilayani'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm font-medium mb-2">Selesai</p>
            <p class="text-3xl font-bold text-green-600">{{ $statistics['selesai'] }}</p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('dashboard') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Nama atau Telepon</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Ketik nama atau nomor telepon...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10">
                        <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="dilayani" {{ request('status') === 'dilayani' ? 'selected' : '' }}>Dilayani</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Cari
                    </button>
                    <a href="{{ route('dashboard') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition text-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Quick Links -->
    <div class="flex gap-2 mb-6">
        <a href="{{ route('guests.form') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            ➕ Tambah Tamu Baru
        </a>
        <a href="{{ route('reports.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            📊 Lihat Laporan
        </a>
    </div>

    <!-- Export Options -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Export Data</h3>
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                <div class="flex items-end gap-2">
                    <a href="{{ route('reports.export.excel', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" 
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition text-center">
                        📥 Export Excel
                    </a>
                    <a href="{{ route('reports.export.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" 
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition text-center">
                        📄 Export PDF
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Telepon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Alamat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Keperluan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($guests as $guest)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $guest->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $guest->phone }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($guest->address, 30) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($guest->purpose, 30) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <form method="POST" action="{{ route('guests.updateStatus', $guest->id) }}" class="inline-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                    class="px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="menunggu" {{ $guest->status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="dilayani" {{ $guest->status === 'dilayani' ? 'selected' : '' }}>Dilayani</option>
                                    <option value="selesai" {{ $guest->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $guest->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <form method="POST" action="{{ route('guests.destroy', $guest->id) }}" class="inline"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data tamu
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $guests->links() }}
</div>
@endsection
