<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') SITADIGI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --font-sans: "Space Grotesk", "Segoe UI", Arial, sans-serif;
            --font-display: "Fraunces", "Space Grotesk", "Times New Roman", serif;
        }
        body { font-family: var(--font-sans); }
        .font-display { font-family: var(--font-display); }
    </style>
</head>
<body class="min-h-screen bg-[#f0f5ff] text-slate-900">
    <div class="fixed inset-0 -z-10">
        <div class="absolute -top-32 -right-24 h-72 w-72 rounded-full bg-[#b9d9ff] blur-3xl opacity-70"></div>
        <div class="absolute -bottom-32 left-0 h-80 w-80 rounded-full bg-[#9cc6ff] blur-3xl opacity-60"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.75),_rgba(240,246,255,0.7),_rgba(225,235,250,0.95))]"></div>
    </div>

    <div class="relative flex min-h-screen">
        <!-- Sidebar Navigation -->
        <aside class="fixed inset-y-0 left-0 z-20 flex w-72 flex-col border-r border-white/60 bg-white/70 shadow-xl shadow-slate-900/5 backdrop-blur">
            <div class="p-8">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/bnn-logo.png') }}" alt="Logo" class="w-12 h-12 rounded-2xl bg-white p-2 shadow-md">
                    <div>
                        <p class="text-[11px] uppercase tracking-[0.3em] text-slate-500">Dashboard</p>
                        <h1 class="text-lg font-semibold text-slate-900">SITADIGI</h1>
                    </div>
                </div>
            </div>
            
            <nav class="px-6">
                <ul class="space-y-2 px-4">
                    <!-- Dashboard Link -->
                    <li>
                        <a href="{{ route('dashboard') }}" 
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/25' : 'text-slate-700 hover:bg-blue-50 hover:text-slate-900' }}">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/80 text-base shadow-sm">
                                <svg class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 3v18h18" />
                                    <path d="M7 15v-6" />
                                    <path d="M12 15V9" />
                                    <path d="M17 15V6" />
                                </svg>
                            </span>
                            Dashboard
                        </a>
                    </li>
                    
                    <!-- Data Kunjungan Link -->
                    <li>
                        <a href="{{ route('guests.index') }}" 
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('guests.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/25' : 'text-slate-700 hover:bg-blue-50 hover:text-slate-900' }}">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/80 text-base shadow-sm">
                                <svg class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 6h6" />
                                    <path d="M9 10h6" />
                                    <path d="M9 14h6" />
                                    <path d="M7 4h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z" />
                                </svg>
                            </span>
                            Data Kunjungan
                        </a>
                    </li>
                    
                    <!-- Export Data Link -->
                    <li>
                        <a href="{{ route('reports.index') }}" 
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('reports.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/25' : 'text-slate-700 hover:bg-blue-50 hover:text-slate-900' }}">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/80 text-base shadow-sm">
                                <svg class="h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" />
                                    <path d="M14 3v5h5" />
                                    <path d="M12 12v6" />
                                    <path d="M9 15l3 3 3-3" />
                                </svg>
                            </span>
                            Laporan
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- User Info & Logout -->
            <div class="mt-auto p-6 border-t border-white/70">
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Masuk sebagai</div>
                <div class="mt-2 text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full rounded-2xl bg-slate-900 py-2.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:bg-slate-800">
                        Logout
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto pl-72">
            <!-- Top Header -->
            <header class="sticky top-0 z-10 border-b border-white/60 bg-white/80 px-8 py-5 backdrop-blur">
                <h2 class="font-display text-2xl text-slate-900">@yield('page_title')</h2>
            </header>
            
            <!-- Content Area -->
            <div class="p-8 space-y-6">
                <!-- Flash Messages -->
                @if ($message = Session::get('success'))
                    <div class="flex items-center justify-between rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                        <span>✅ {{ $message }}</span>
                        <button onclick="this.parentElement.style.display='none'" class="text-emerald-600 hover:text-emerald-800">×</button>
                    </div>
                @endif
                
                @if ($message = Session::get('error'))
                    <div class="flex items-center justify-between rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700">
                        <span>❌ {{ $message }}</span>
                        <button onclick="this.parentElement.style.display='none'" class="text-rose-600 hover:text-rose-800">×</button>
                    </div>
                @endif
                
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3">
                        <ul class="list-disc list-inside text-rose-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
