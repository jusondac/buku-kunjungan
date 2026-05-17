@extends('dashboard-layout')

@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Time Filter -->
    <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10">
        <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
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
                <input type="date" name="start_date" value="{{ request('start_date', $filterDates['start_date']) }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ request('end_date', $filterDates['end_date']) }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            </div>
            <div class="flex items-end gap-2 md:col-span-2">
                <button type="submit" class="flex-1 rounded-2xl bg-blue-600 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-500">
                    Terapkan
                </button>
                <a href="{{ route('dashboard') }}" class="flex-1 rounded-2xl border border-slate-200 bg-white/80 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-900 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>
    <!-- Purpose Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Rata-rata Keperluan</h3>
                <span class="text-sm text-slate-500">{{ $purposeTotal }} tamu</span>
            </div>
            <div class="h-64">
                <canvas id="purposeAverageChart" class="w-full h-full"></canvas>
            </div>
        </div>
        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Total Keperluan</h3>
                <span class="text-sm text-slate-500">Jumlah per kategori</span>
            </div>
            <div class="h-64">
                <canvas id="purposeTotalChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>
    <!-- Additional Status Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total Tamu</p>
            <p class="mt-3 text-3xl font-semibold text-blue-600">{{ $statistics['total'] }}</p>
        </div>
        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total Selesai</p>
            <p class="mt-3 text-3xl font-semibold text-emerald-600">{{ $statistics['selesai'] }}</p>
        </div>
        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total Dilayani</p>
            <p class="mt-3 text-3xl font-semibold text-blue-600">{{ $statistics['dilayani'] }}</p>
        </div>
        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total Menunggu</p>
            <p class="mt-3 text-3xl font-semibold text-amber-500">{{ $statistics['menunggu'] }}</p>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Status Distribution -->
        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Distribusi Status</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">Menunggu</span>
                        <span class="text-sm font-bold text-amber-500">
                            {{ $statistics['total'] > 0 ? round(($statistics['menunggu'] / $statistics['total']) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3">
                        <div class="bg-amber-400 h-3 rounded-full" style="width: {{ $statistics['total'] > 0 ? round(($statistics['menunggu'] / $statistics['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">Dilayani</span>
                        <span class="text-sm font-bold text-blue-600">
                            {{ $statistics['total'] > 0 ? round(($statistics['dilayani'] / $statistics['total']) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $statistics['total'] > 0 ? round(($statistics['dilayani'] / $statistics['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">Selesai</span>
                        <span class="text-sm font-bold text-emerald-600">
                            {{ $statistics['total'] > 0 ? round(($statistics['selesai'] / $statistics['total']) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3">
                        <div class="bg-emerald-500 h-3 rounded-full" style="width: {{ $statistics['total'] > 0 ? round(($statistics['selesai'] / $statistics['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="rounded-3xl border border-white/70 bg-white/80 p-6 shadow-xl shadow-slate-900/10">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('guests.index') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 py-3 px-4 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-500">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 6h6" />
                        <path d="M9 10h6" />
                        <path d="M9 14h6" />
                        <path d="M7 4h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z" />
                    </svg>
                    Lihat Data Kunjungan
                </a>
                <a href="{{ route('guests.form') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white/90 py-3 px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-900">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 5v14" />
                        <path d="M5 12h14" />
                    </svg>
                    Tambah Tamu Baru
                </a>
                <a href="{{ route('reports.index') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-blue-200 bg-blue-50 py-3 px-4 text-sm font-semibold text-blue-700 shadow-sm transition hover:border-blue-300">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" />
                        <path d="M14 3v5h5" />
                        <path d="M12 12v6" />
                        <path d="M9 15l3 3 3-3" />
                    </svg>
                    Export Data
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Text -->
    <div class="rounded-3xl border border-blue-200 bg-blue-50 p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-2">Ringkasan</h3>
        <p class="text-blue-800">
            Total <strong>{{ $statistics['total'] }}</strong> tamu terdaftar. 
            Saat ini ada <strong>{{ $statistics['menunggu'] }}</strong> tamu menunggu, 
            <strong>{{ $statistics['dilayani'] }}</strong> sedang dilayani, 
            dan <strong>{{ $statistics['selesai'] }}</strong> sudah selesai.
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const purposeLabels = @json($purposeLabels ?? []);
    const purposeValues = @json($purposeValues ?? []);
    const purposePercentages = @json($purposePercentages ?? []);

    const averageContext = document.getElementById('purposeAverageChart');
    const totalContext = document.getElementById('purposeTotalChart');

    const chartColors = [
        '#2563eb',
        '#16a34a',
        '#f59e0b',
        '#7c3aed',
        '#dc2626',
        '#0d9488',
        '#9333ea'
    ];

    if (averageContext && purposeLabels.length) {
        new Chart(averageContext, {
            type: 'doughnut',
            data: {
                labels: purposeLabels,
                datasets: [
                    {
                        data: purposePercentages,
                        backgroundColor: chartColors,
                        borderWidth: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => `${context.label}: ${context.raw}%`
                        }
                    }
                }
            }
        });
    }

    if (totalContext && purposeLabels.length) {
        new Chart(totalContext, {
            type: 'bar',
            data: {
                labels: purposeLabels,
                datasets: [
                    {
                        label: 'Total Keperluan',
                        data: purposeValues,
                        backgroundColor: chartColors,
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
</script>
@endsection
