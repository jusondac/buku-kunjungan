@extends('dashboard-layout')

@section('page_title', 'Data Kunjungan')

@section('content')
<div class="space-y-6">
    <!-- Statistics Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-xl shadow-slate-900/10">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $statistics['total'] }}</p>
        </div>
        <div class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-xl shadow-slate-900/10">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Menunggu</p>
            <p class="mt-2 text-2xl font-semibold text-amber-500">{{ $statistics['menunggu'] }}</p>
        </div>
        <div class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-xl shadow-slate-900/10">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Dilayani</p>
            <p class="mt-2 text-2xl font-semibold text-blue-600">{{ $statistics['dilayani'] }}</p>
        </div>
        <div class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-xl shadow-slate-900/10">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Selesai</p>
            <p class="mt-2 text-2xl font-semibold text-emerald-600">{{ $statistics['selesai'] }}</p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10">
        <form method="GET" action="{{ route('guests.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Cari Nama atau Telepon</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        placeholder="Ketik nama atau nomor telepon...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Filter Status</label>
                    <select name="status"
                        class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="dilayani" {{ request('status') === 'dilayani' ? 'selected' : '' }}>Dilayani</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Periode</label>
                    <select name="periode"
                        class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <option value="hari_ini" {{ request('periode', 'hari_ini') === 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="seminggu" {{ request('periode') === 'seminggu' ? 'selected' : '' }}>Seminggu Terakhir</option>
                        <option value="sebulan" {{ request('periode') === 'sebulan' ? 'selected' : '' }}>Sebulan Terakhir</option>
                        <option value="setahun" {{ request('periode') === 'setahun' ? 'selected' : '' }}>Setahun Terakhir</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div class="flex items-end gap-2 md:col-span-2">
                    <button type="submit" class="flex-1 rounded-2xl bg-blue-600 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-500">
                        Cari
                    </button>
                    <a href="{{ route('guests.index') }}" class="flex-1 rounded-2xl border border-slate-200 bg-white/80 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-900 text-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="rounded-3xl border border-white/70 bg-white/90 shadow-xl shadow-slate-900/10 overflow-hidden">
        @if($guests->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Telepon</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Alamat</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Keperluan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($guests as $guest)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $guest->name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $guest->phone }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($guest->address, 25) }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($guest->purpose, 25) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($guest->status === 'selesai')
                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            Selesai
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('guests.updateStatus', $guest->id) }}" class="inline-flex gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" data-current="{{ $guest->status }}" onchange="if (this.value === 'selesai' && !confirm('Tandai status sebagai selesai?')) { this.value = this.dataset.current; return; } this.form.submit();"
                                                class="rounded-xl border border-slate-200 bg-white/80 px-3 py-1 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                                <option value="menunggu" {{ $guest->status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                <option value="dilayani" {{ $guest->status === 'dilayani' ? 'selected' : '' }}>Dilayani</option>
                                                <option value="selesai" {{ $guest->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $guest->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($guest->status === 'selesai')
                                        <span class="text-slate-400 cursor-not-allowed">Hapus</span>
                                    @else
                                        <form method="POST" action="{{ route('guests.destroy', $guest->id) }}" class="inline"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                {{ $guests->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-slate-500 text-lg">Tidak ada data tamu</p>
            </div>
        @endif
    </div>
</div>
@endsection
