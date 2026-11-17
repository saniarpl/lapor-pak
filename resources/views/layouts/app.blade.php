<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Laporan Kerusakan Sekolah</title>
    {{-- Tailwind via CDN agar langsung jalan di Laragon tanpa npm. Untuk produksi, ganti dengan Vite/Tailwind build. --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen">
@auth
    @php
        $isAdmin = auth()->user()->isAdmin();
        $nav = $isAdmin
            ? [
                ['Dashboard', route('dashboard'), 'dashboard'],
                ['Laporan', route('reports.index'), 'reports.*'],
                ['Siswa', route('admin.students.index'), 'admin.students.*'],
                ['Kategori', route('admin.categories.index'), 'admin.categories.*'],
                ['Urgensi', route('admin.urgencies.index'), 'admin.urgencies.*'],
                ['Petugas', route('admin.workers.index'), 'admin.workers.*'],
                ['Riwayat', route('admin.histories.index'), 'admin.histories.*'],
            ]
            : [
                ['Dashboard', route('dashboard'), 'dashboard'],
                ['Laporan Saya', route('reports.index'), 'reports.index'],
                ['Buat Laporan', route('reports.create'), 'reports.create'],
            ];
    @endphp
    <nav class="no-print bg-indigo-700 text-white shadow">
        <div class="max-w-6xl mx-auto px-4 py-3 flex flex-wrap items-center gap-x-4 gap-y-2">
            <span class="font-bold mr-2">Lapor Fasilitas Sekolah</span>
            @foreach ($nav as [$label, $url, $pattern])
                <a href="{{ $url }}"
                   class="px-2 py-1 rounded text-sm {{ request()->routeIs($pattern) ? 'bg-indigo-900' : 'hover:bg-indigo-600' }}">{{ $label }}</a>
            @endforeach
            <div class="ml-auto flex items-center gap-3 text-sm">
                <span>{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-white text-indigo-700 px-3 py-1 rounded hover:bg-indigo-50">Keluar</button>
                </form>
            </div>
        </div>
    </nav>
@endauth

<main class="max-w-6xl mx-auto px-4 py-6">
    @if (session('success'))
        <div class="no-print mb-4 rounded bg-green-100 border border-green-300 text-green-800 px-4 py-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="no-print mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-3">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="no-print mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-3">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>