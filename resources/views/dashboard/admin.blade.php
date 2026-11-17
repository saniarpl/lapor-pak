@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Dashboard Admin</h1>
        <a href="{{ route('reports.print') }}" target="_blank" class="bg-white border px-3 py-2 rounded text-sm hover:bg-slate-50">Cetak Semua Laporan</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3 mb-6">
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-slate-500">Total Laporan</div>
            <div class="text-2xl font-bold">{{ $total }}</div>
        </div>
        @foreach ($statusCounts as $status => $count)
            <a href="{{ route('reports.index', ['status' => $status]) }}" class="bg-white rounded shadow p-4 hover:ring-2 hover:ring-indigo-300">
                <div class="text-xs text-slate-500">{{ $status }}</div>
                <div class="text-2xl font-bold">{{ $count }}</div>
            </a>
        @endforeach
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-slate-500">Siswa / Petugas</div>
            <div class="text-2xl font-bold">{{ $studentCount }} / {{ $workerCount }}</div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded shadow p-4">
            <h2 class="font-semibold mb-3">Laporan per Kategori</h2>
            @php $maxCat = max(1, $byCategory->max('reports_count')); @endphp
            @foreach ($byCategory as $cat)
                <div class="mb-2">
                    <div class="flex justify-between text-sm"><span>{{ $cat->name }}</span><span>{{ $cat->reports_count }}</span></div>
                    <div class="h-2 bg-slate-100 rounded"><div class="h-2 bg-indigo-500 rounded" style="width: {{ ($cat->reports_count / $maxCat) * 100 }}%"></div></div>
                </div>
            @endforeach
        </div>
        <div class="bg-white rounded shadow p-4">
            <h2 class="font-semibold mb-3">Laporan per Urgensi</h2>
            @php $maxUrg = max(1, $byUrgency->max('reports_count')); @endphp
            @foreach ($byUrgency as $urg)
                <div class="mb-2">
                    <div class="flex justify-between text-sm"><span>{{ $urg->name }}</span><span>{{ $urg->reports_count }}</span></div>
                    <div class="h-2 bg-slate-100 rounded"><div class="h-2 bg-rose-500 rounded" style="width: {{ ($urg->reports_count / $maxUrg) * 100 }}%"></div></div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded shadow p-4">
        <h2 class="font-semibold mb-3">Laporan Terbaru</h2>
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500">
                <tr><th class="py-1">Tiket</th><th>Siswa</th><th>Kategori</th><th>Urgensi</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse ($recent as $r)
                    <tr class="border-t">
                        <td class="py-2"><a class="text-indigo-600 hover:underline" href="{{ route('reports.show', $r) }}">{{ $r->ticket_number }}</a></td>
                        <td>{{ $r->user->name }}</td>
                        <td>{{ $r->category->name }}</td>
                        <td>{{ $r->urgency->name }}</td>
                        <td>@include('partials.status-badge', ['status' => $r->status])</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-center text-slate-500">Belum ada laporan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection