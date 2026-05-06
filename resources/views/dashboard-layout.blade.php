<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Buku Kunjungan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-blue-900 text-white shadow-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold">📕 Buku Kunjungan</h1>
            </div>
            
            <nav class="mt-8">
                <ul class="space-y-2 px-4">
                    <!-- Dashboard Link -->
                    <li>
                        <a href="{{ route('dashboard') }}" 
                            class="block px-4 py-3 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-800' }}">
                            📊 Dashboard
                        </a>
                    </li>
                    
                    <!-- Data Kunjungan Link -->
                    <li>
                        <a href="{{ route('guests.index') }}" 
                            class="block px-4 py-3 rounded-lg transition {{ request()->routeIs('guests.index') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-800' }}">
                            📋 Data Kunjungan
                        </a>
                    </li>
                    
                    <!-- Export Data Link -->
                    <li>
                        <a href="{{ route('reports.index') }}" 
                            class="block px-4 py-3 rounded-lg transition {{ request()->routeIs('reports.index') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-800' }}">
                            📥 Export Data
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- User Info & Logout -->
            <div class="absolute bottom-0 left-0 right-0 w-64 p-4 border-t border-blue-800">
                <div class="text-sm text-blue-100 mb-3">
                    Masuk sebagai: <strong>{{ Auth::user()->name }}</strong>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Logout
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 px-8 py-4 shadow-sm">
                <h2 class="text-2xl font-bold text-gray-800">@yield('page_title')</h2>
            </header>
            
            <!-- Content Area -->
            <div class="p-8">
                <!-- Flash Messages -->
                @if ($message = Session::get('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex justify-between items-center">
                        <span class="text-green-700">✅ {{ $message }}</span>
                        <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-800">×</button>
                    </div>
                @endif
                
                @if ($message = Session::get('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex justify-between items-center">
                        <span class="text-red-700">❌ {{ $message }}</span>
                        <button onclick="this.parentElement.style.display='none'" class="text-red-600 hover:text-red-800">×</button>
                    </div>
                @endif
                
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="list-disc list-inside text-red-600">
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
