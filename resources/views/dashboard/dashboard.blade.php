@extends('dashboard-layout')

@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Main Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Tamu -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-600">
            <p class="text-gray-600 text-sm font-medium mb-2">Total Tamu</p>
            <p class="text-4xl font-bold text-blue-600">{{ $statistics['total'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Bulan ini: {{ $thisMonth['total'] }}</p>
        </div>

        <!-- Menunggu -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-yellow-500">
            <p class="text-gray-600 text-sm font-medium mb-2">Menunggu</p>
            <p class="text-4xl font-bold text-yellow-500">{{ $statistics['menunggu'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Bulan ini: {{ $thisMonth['menunggu'] }}</p>
        </div>

        <!-- Dilayani -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
            <p class="text-gray-600 text-sm font-medium mb-2">Dilayani</p>
            <p class="text-4xl font-bold text-blue-500">{{ $statistics['dilayani'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Bulan ini: {{ $thisMonth['dilayani'] }}</p>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-green-600">
            <p class="text-gray-600 text-sm font-medium mb-2">Selesai</p>
            <p class="text-4xl font-bold text-green-600">{{ $statistics['selesai'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Bulan ini: {{ $thisMonth['selesai'] }}</p>
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
