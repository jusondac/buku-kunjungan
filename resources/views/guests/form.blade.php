@extends('layout')

@section('title', 'Form Tamu')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="grid gap-8 lg:grid-cols-[1fr_1.3fr] items-start">
        <div class="space-y-6">
            <div class="inline-flex items-center gap-2 rounded-full bg-white/70 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">
                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                Layanan kunjungan aktif
            </div>
            <div>
                <h2 class="font-display text-4xl sm:text-5xl text-slate-900">Buku Kunjungan Digital</h2>
                <p class="mt-4 text-base text-slate-600">Isi data kunjungan dengan cepat dan rapi. Sistem ini menyimpan jejak kunjungan secara aman dan mudah diakses oleh petugas.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-white/70 bg-white/70 p-4 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Responsif</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">Siap di ponsel</p>
                    <p class="mt-1 text-sm text-slate-600">Formulir nyaman dipakai di berbagai perangkat.</p>
                </div>
                <div class="rounded-2xl border border-white/70 bg-white/70 p-4 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Terstruktur</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">Data rapi</p>
                    <p class="mt-1 text-sm text-slate-600">Memudahkan pelacakan dan pelaporan.</p>
                </div>
            </div>
            <div class="rounded-2xl border border-white/70 bg-white/70 p-5 text-sm text-slate-700 shadow-sm">
                <p class="font-semibold text-slate-900">Catatan singkat</p>
                <p class="mt-2">Pastikan data yang Anda isi lengkap agar proses verifikasi berjalan cepat.</p>
            </div>
        </div>

        <div class="rounded-3xl border border-white/80 bg-white/90 p-8 shadow-xl shadow-slate-900/10 backdrop-blur">
            <h3 class="text-2xl font-semibold text-slate-900">Formulir Tamu</h3>
            <p class="mt-2 text-sm text-slate-600">Silakan isi data di bawah ini untuk mendaftar sebagai tamu.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3">
                <ul class="list-disc list-inside text-rose-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('guests.store') }}" class="mt-6 space-y-6"
            x-data="{ purposeType: '{{ old('purpose', '') }}', purposeLainnya: '{{ old('purpose_lainnya', '') }}' }"
            x-on:reset.prevent="
                Array.from($el.querySelectorAll('input, textarea, select')).forEach((el) => {
                    if (el.type === 'hidden') return;
                    if (el.type === 'checkbox' || el.type === 'radio') { el.checked = false; return; }
                    el.value = '';
                });
                purposeType = '';
                purposeLainnya = '';
            "
        >
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    placeholder="Masukkan nama lengkap" required>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                    Email <span class="text-gray-500">(Opsional)</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    placeholder="Masukkan email Anda (opsional)">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                    Nomor Telepon <span class="text-red-500">*</span>
                </label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" inputmode="numeric" pattern="[0-9]*"
                    class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    placeholder="Masukkan nomor telepon (angka saja)" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-slate-700 mb-2">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <textarea id="address" name="address" rows="3"
                    class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    placeholder="Masukkan alamat lengkap" required>{{ old('address') }}</textarea>
            </div>

            <div x-effect="if (purposeType !== 'lainnya') { purposeLainnya = '' }">
                <label for="purpose" class="block text-sm font-medium text-slate-700 mb-2">
                    Keperluan <span class="text-red-500">*</span>
                </label>
                <select id="purpose" name="purpose" x-model="purposeType"
                    class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    required>
                    <option value="">Pilih Keperluan</option>
                    <option value="rehabilitas">Rehabilitas</option>
                    <option value="skhpn">SKHPN</option>
                    <option value="bagian umum">Bagian Umum</option>
                    <option value="pemberantasan">Pemberantasan</option>
                    <option value="lainnya">Lainnya</option>
                </select>
                
                <!-- Conditional field for "lainnya" -->
                <div x-show="purposeType === 'lainnya'" x-cloak class="mt-4">
                    <label for="purpose_lainnya" class="block text-sm font-medium text-slate-700 mb-2">
                        Keperluan Lainnya <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="purpose_lainnya" name="purpose_lainnya" x-model="purposeLainnya"
                        class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        placeholder="Masukkan keperluan lainnya"
                        x-bind:required="purposeType === 'lainnya'">
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 rounded-2xl bg-blue-600 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-500">
                    Simpan
                </button>
                <button type="reset" class="flex-1 rounded-2xl border border-slate-200 bg-white/80 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-900">
                    Ulang
                </button>
            </div>
        </form>

        @auth
            <div class="mt-4 text-center">
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-700 hover:text-slate-900">
                    ← Kembali ke Dashboard
                </a>
            </div>
        @endauth
    </div>
</div>
</div>
@endsection
