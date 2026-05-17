@extends('dashboard-layout')

@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Time Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                <select name="periode"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="hari_ini" {{ request('periode', 'hari_ini') === 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="seminggu" {{ request('periode') === 'seminggu' ? 'selected' : '' }}>Seminggu Terakhir</option>
                    <option value="sebulan" {{ request('periode') === 'sebulan' ? 'selected' : '' }}>Sebulan Terakhir</option>
                    <option value="setahun" {{ request('periode') === 'setahun' ? 'selected' : '' }}>Setahun Terakhir</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date', $filterDates['start_date']) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ request('end_date', $filterDates['end_date']) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-end gap-2 md:col-span-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    Terapkan
                </button>
                <a href="{{ route('dashboard') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>
    <!-- Additional Status Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
            <p class="text-gray-600 text-sm font-medium mb-2">Total Tamu</p>
            <p class="text-3xl font-bold text-blue-600">{{ $statistics['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-green-600">
            <p class="text-gray-600 text-sm font-medium mb-2">Total Selesai</p>
            <p class="text-3xl font-bold text-green-600">{{ $statistics['selesai'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
            <p class="text-gray-600 text-sm font-medium mb-2">Total Dilayani</p>
            <p class="text-3xl font-bold text-blue-600">{{ $statistics['dilayani'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-yellow-600">
            <p class="text-gray-600 text-sm font-medium mb-2">Total Menunggu</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $statistics['menunggu'] }}</p>
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
