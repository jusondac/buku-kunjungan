@extends('dashboard-layout')

@section('page_title', 'Export Data')

@section('content')
<div class="space-y-6">
    <!-- Filter Form -->
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Filter Laporan</h3>
        <form method="GET" action="{{ route('reports.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Filter Tanggal</label>
                    <select name="date_filter" onchange="this.form.submit()"
                        class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <option value="">Semua Tanggal</option>
                        <option value="hari_ini" {{ request('date_filter') === 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="kemarin" {{ request('date_filter') === 'kemarin' ? 'selected' : '' }}>Kemarin</option>
                        <option value="seminggu_terakhir" {{ request('date_filter') === 'seminggu_terakhir' ? 'selected' : '' }}>Seminggu Terakhir</option>
                        <option value="sebulan_terakhir" {{ request('date_filter') === 'sebulan_terakhir' ? 'selected' : '' }}>Sebulan Terakhir</option>
                        <option value="setahun_terakhir" {{ request('date_filter') === 'setahun_terakhir' ? 'selected' : '' }}>Setahun Terakhir</option>
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
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Keperluan</label>
                    <select name="keperluan"
                        class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <option value="">Semua Keperluan</option>
                        @foreach($kperluanOptions as $option)
                            <option value="{{ $option }}" {{ request('keperluan') === $option ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('-', ' ', $option)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-500">
                    Tampilkan
                </button>
                <a href="{{ route('reports.index') }}" class="rounded-2xl border border-slate-200 bg-white/80 px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-900 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if(request('date_filter') || request('start_date') || request('end_date') || request('keperluan'))
        <!-- Export Buttons -->
        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Export Data</h3>
            <div class="flex gap-4">
                <a href="{{ route('reports.export.pdf', ['date_filter' => request('date_filter'), 'start_date' => request('start_date'), 'end_date' => request('end_date'), 'keperluan' => request('keperluan')]) }}" 
                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-blue-600 py-3 px-4 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-500">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" />
                        <path d="M14 3v5h5" />
                        <path d="M9 13h6" />
                        <path d="M9 17h6" />
                    </svg>
                    Unduh Laporan
                </a>
            </div>
        </div>

        <!-- Data Preview Table -->
        <div class="rounded-3xl border border-white/70 bg-white/90 shadow-xl shadow-slate-900/10 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Preview Data ({{ $guests->count() }} tamu)</h3>
            </div>
            @if($guests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Telepon</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Keperluan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($guests->take(20) as $index => $guest)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $guest->name }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $guest->phone }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($guest->purpose, 30) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($guest->status === 'menunggu')
                                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Menunggu</span>
                                        @elseif($guest->status === 'dilayani')
                                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Dilayani</span>
                                        @else
                                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $guest->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($guests->count() > 20)
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 text-sm text-slate-600">
                        Menampilkan 20 dari {{ $guests->count() }} data. Download untuk melihat semua data.
                    </div>
                @endif
            @else
                <div class="px-6 py-12 text-center">
                    <p class="text-slate-500 text-lg">Tidak ada data sesuai filter</p>
                </div>
            @endif
        </div>
    @else
        <div class="rounded-3xl border border-blue-200 bg-blue-50 p-8 text-center">
            <p class="text-blue-800 text-lg">Pilih filter dan klik "Tampilkan" untuk melihat data dan export</p>
        </div>
    @endif
</div>
@endsection
