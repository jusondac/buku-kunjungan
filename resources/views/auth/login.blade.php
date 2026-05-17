@extends('layout')

@section('title', 'Login Petugas')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="grid overflow-hidden rounded-3xl border border-white/70 bg-white/80 shadow-xl shadow-slate-900/10 backdrop-blur lg:grid-cols-[1fr_1.1fr]">
        <div class="relative p-8 sm:p-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(37,99,235,0.18),_rgba(255,255,255,0.85))]"></div>
            <div class="relative space-y-6">
                <img src="{{ asset('image/bnn-logo.png') }}" alt="Logo" class="w-20 rounded-2xl bg-white p-2 shadow-md">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">SITADIGI</p>
                    <h2 class="font-display text-3xl text-slate-900">Login Petugas</h2>
                    <p class="mt-3 text-sm text-slate-600">Akses dashboard untuk memantau data kunjungan, status tamu, dan laporan terbaru.</p>
                </div>
                <div class="grid gap-3">
                    <div class="flex items-center gap-3 rounded-2xl border border-white/70 bg-white/80 px-4 py-3 text-sm text-slate-700 shadow-sm">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                        Verifikasi data otomatis
                    </div>
                    <div class="flex items-center gap-3 rounded-2xl border border-white/70 bg-white/80 px-4 py-3 text-sm text-slate-700 shadow-sm">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-400"></span>
                        Laporan tersusun rapi
                    </div>
                    <div class="flex items-center gap-3 rounded-2xl border border-white/70 bg-white/80 px-4 py-3 text-sm text-slate-700 shadow-sm">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-300"></span>
                        Riwayat kunjungan real time
                    </div>
                </div>
                <div>
                    <a href="{{ route('guests.form') }}" class="inline-flex items-center text-sm font-semibold text-slate-700 hover:text-slate-900">
                        Daftar sebagai tamu
                    </a>
                </div>
            </div>
        </div>

        <div class="p-8 sm:p-10">
            <h3 class="text-xl font-semibold text-slate-900">Masuk ke akun petugas</h3>
            <p class="mt-2 text-sm text-slate-600">Gunakan email resmi dan kata sandi yang terdaftar.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3">
                <ul class="list-disc list-inside text-rose-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="mt-6 space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    placeholder="Masukkan email" required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" id="password" name="password"
                    class="w-full rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    placeholder="Masukkan password" required>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" class="rounded border-slate-300 text-blue-600">
                <label for="remember" class="ml-2 text-sm text-slate-600">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="w-full rounded-2xl bg-blue-600 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-500">
                Login
            </button>
        </form>

            <div class="mt-6 text-center text-sm text-slate-600">
                Butuh bantuan? Hubungi admin untuk reset akses.
            </div>
        </div>
    </div>
</div>
@endsection
