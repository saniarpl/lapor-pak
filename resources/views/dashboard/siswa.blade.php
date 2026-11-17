@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold">Halo, {{ auth()->user()->name }}</h1>
            <p class="text-sm text-slate-500">{{ auth()->user()->class_name }}</p>
        </div>
        <a href="{{ route('reports.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">+ Buat Laporan</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6">
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-slate-500">Total Laporan Saya</div>
            <div class="text-2xl font-bold">{{ $total }}</div>
        </div>
        @foreach ($statusCounts as $status => $count)
            <a href="{{ route('reports.index', ['status' => $status]) }}" class="bg-white rounded shadow p-4 hover:ring-2 hover:ring-indigo-300">
                <div class="text-xs text-slate-500">{{ $status }}</div>
                <div class="text-2xl font-bold">{{ $count }}</div>
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded shadow p-4">
        <h2 class="font-semibold mb-3">Laporan Terbaru Saya</h2>
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500">
                <tr><th class="py-1">Tiket</th><th>Kategori</th><th>Lokasi</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse ($recent as $r)
                    <tr class="border-t">
                        <td class="py-2"><a class="text-indigo-600 hover:underline" href="{{ route('reports.show', $r) }}">{{ $r->ticket_number }}</a></td>
                        <td>{{ $r->category->name }}</td>
                        <td>{{ $r->location }}</td>
                        <td>@include('partials.status-badge', ['status' => $r->status])</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-center text-slate-500">Anda belum membuat laporan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection