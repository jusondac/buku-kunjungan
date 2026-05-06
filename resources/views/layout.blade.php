<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Buku Kunjungan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <h1 class="text-xl font-bold">📕 Buku Kunjungan</h1>
                </div>
                <div class="flex items-center space-x-4">
                    @guest
                  <a href="{{ route('login') }}" class="bg-white text-blue-600 font-semibold px-4 py-2 rounded hover:bg-gray-100 transition">
                      Masuk
                  </a>
              @endguest
              @auth
                  <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 font-semibold px-4 py-2 rounded hover:bg-gray-100 transition">
                      Dashboard
                  </a>
              @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if ($message = Session::get('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ $message }}
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ $message }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
