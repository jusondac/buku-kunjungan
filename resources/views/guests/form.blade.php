@extends('layout')

@section('title', 'Form Tamu')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
        <p class="text-gray-600 mb-8">Silakan isi formulir di bawah ini untuk mendaftar sebagai tamu.</p>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                <ul class="list-disc list-inside text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('guests.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Masukkan nama lengkap" required>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-gray-500">(Opsional)</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Masukkan email Anda (opsional)">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Telepon <span class="text-red-500">*</span>
                </label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" inputmode="numeric" pattern="[0-9]*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Masukkan nomor telepon (angka saja)" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <textarea id="address" name="address" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Masukkan alamat lengkap" required>{{ old('address') }}</textarea>
            </div>

            <div x-data="{ purposeType: '{{ old('purpose', '') }}' }">
                <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                    Keperluan <span class="text-red-500">*</span>
                </label>
                <select id="purpose" name="purpose" x-model="purposeType"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
                    <option value="">Pilih Keperluan</option>
                    <option value="rehabilitas">Rehabilitas</option>
                    <option value="skhpn">SKHPN</option>
                    <option value="bagian umum">Bagian Umum</option>
                    <option value="pemberantasan">Pemberantasan</option>
                    <option value="lainnya">Lainnya</option>
                </select>
                
                <!-- Conditional field for "lainnya" -->
                <div x-show="purposeType === 'lainnya'" class="mt-4" style="display: none;">
                    <label for="purpose_lainnya" class="block text-sm font-medium text-gray-700 mb-2">
                        Keperluan Lainnya <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="purpose_lainnya" name="purpose_lainnya" 
                        value="{{ old('purpose_lainnya', '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Jelaskan keperluan Anda"
                        x-bind:required="purposeType === 'lainnya'">
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                    Simpan
                </button>
                <button type="reset" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                    Ulang
                </button>
            </div>
        </form>

        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-gray-700">
                <strong>Catatan:</strong> Data Anda akan diproses oleh petugas. Status kunjungan akan diperbarui sesuai dengan proses yang berlangsung.
            </p>
        </div>

        @auth
            <div class="mt-4 text-center">
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                    ← Kembali ke Dashboard
                </a>
            </div>
        @endauth
    </div>
</div>
@endsection
