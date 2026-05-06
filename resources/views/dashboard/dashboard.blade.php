@extends('dashboard-layout')

@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Time Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('dashboard') }}" class="flex gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Waktu</label>
                <select name="time_filter" onchange="this.form.submit()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="hari_ini" {{ $timeFilter === 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="kemarin" {{ $timeFilter === 'kemarin' ? 'selected' : '' }}>Kemarin</option>
                    <option value="seminggu_terakhir" {{ $timeFilter === 'seminggu_terakhir' ? 'selected' : '' }}>Seminggu Terakhir</option>
                    <option value="bulan_ini" {{ $timeFilter === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="sebulan_terakhir" {{ $timeFilter === 'sebulan_terakhir' ? 'selected' : '' }}>Sebulan Terakhir</option>
                    <option value="setahun_terakhir" {{ $timeFilter === 'setahun_terakhir' ? 'selected' : '' }}>Setahun Terakhir</option>
                </select>
            </div>
        </form>
    </div>
    <!-- Main Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Tamu -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
            <p class="text-gray-600 text-sm font-medium mb-2">Total Tamu</p>
            <p class="text-4xl font-bold text-blue-600">{{ $statistics['total'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Bulan ini: {{ $thisMonth['total'] }}</p>
        </div>

        <!-- Status Kunjungan (Combined) -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-purple-600">
            <p class="text-gray-600 text-sm font-medium mb-3">Status Kunjungan</p>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-700">Selesai</span>
                    <span class="text-2xl font-bold text-green-600">{{ $statistics['selesai'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-700">Belum Selesai</span>
                    <span class="text-2xl font-bold text-orange-600">{{ $serviceMetrics['total_not_completed'] }}</span>
                </div>
            </div>
        </div>

        <!-- Total Waktu Layanan -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-cyan-600">
            <p class="text-gray-600 text-sm font-medium mb-2">Total Waktu Layanan</p>
            <p class="text-3xl font-bold text-cyan-600 font-mono">{{ $totalServiceTime }}</p>
            <p class="text-xs text-gray-500 mt-2">Semua kunjungan</p>
        </div>

        <!-- Rata-rata Waktu Layanan -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-indigo-600">
            <p class="text-gray-600 text-sm font-medium mb-2">Rata-rata Waktu</p>
            <p class="text-3xl font-bold text-indigo-600 font-mono">{{ $serviceMetrics['average_service_time'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Per kunjungan selesai</p>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Status Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Status</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Menunggu</span>
                        <span class="text-sm font-bold text-yellow-600">
                            {{ $statistics['total'] > 0 ? round(($statistics['menunggu'] / $statistics['total']) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-yellow-500 h-3 rounded-full" style="width: {{ $statistics['total'] > 0 ? round(($statistics['menunggu'] / $statistics['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Dilayani</span>
                        <span class="text-sm font-bold text-blue-600">
                            {{ $statistics['total'] > 0 ? round(($statistics['dilayani'] / $statistics['total']) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $statistics['total'] > 0 ? round(($statistics['dilayani'] / $statistics['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Selesai</span>
                        <span class="text-sm font-bold text-green-600">
                            {{ $statistics['total'] > 0 ? round(($statistics['selesai'] / $statistics['total']) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full" style="width: {{ $statistics['total'] > 0 ? round(($statistics['selesai'] / $statistics['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('guests.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition text-center">
                    📋 Lihat Data Kunjungan
                </a>
                <a href="{{ route('guests.form') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition text-center">
                    ➕ Tambah Tamu Baru
                </a>
                <a href="{{ route('reports.index') }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition text-center">
                    📥 Export Data
                </a>
            </div>
        </div>
    </div>

    <!-- Timer Analytics Section -->
    @if($timerMetrics['completed_count'] > 0)
        <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg shadow p-6 border-l-4 border-purple-600">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">⏱️ Analitik Waktu Pelayanan</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Total Waktu -->
                <div class="bg-white rounded-lg p-4 border border-purple-200">
                    <p class="text-gray-600 text-xs font-medium mb-2">Total Waktu Semua Kunjungan</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $timerMetrics['total_duration'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $timerMetrics['completed_count'] }} kunjungan selesai</p>
                </div>
                
                <!-- Rata-rata Waktu -->
                <div class="bg-white rounded-lg p-4 border border-blue-200">
                    <p class="text-gray-600 text-xs font-medium mb-2">Rata-rata Waktu Pelayanan</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $timerMetrics['average_duration'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">per kunjungan</p>
                </div>
                
                <!-- Waktu Tercepat -->
                <div class="bg-white rounded-lg p-4 border border-green-200">
                    <p class="text-gray-600 text-xs font-medium mb-2">Waktu Tercepat Pelayanan</p>
                    <p class="text-3xl font-bold text-green-600">{{ $timerMetrics['fastest_duration'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">waktu tercepat</p>
                </div>
                
                <!-- Waktu Terlama -->
                <div class="bg-white rounded-lg p-4 border border-orange-200">
                    <p class="text-gray-600 text-xs font-medium mb-2">Waktu Terlama Pelayanan</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $timerMetrics['slowest_duration'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">waktu terlama</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Summary Text -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-2">Ringkasan</h3>
        <p class="text-blue-800">
            Total <strong>{{ $statistics['total'] }}</strong> tamu terdaftar. 
            Saat ini ada <strong>{{ $statistics['menunggu'] }}</strong> tamu menunggu, 
            <strong>{{ $statistics['dilayani'] }}</strong> sedang dilayani, 
            dan <strong>{{ $statistics['selesai'] }}</strong> sudah selesai.
        </p>
    </div>
</div>
@endsection
