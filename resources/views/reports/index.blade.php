@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    @php $isAdmin = auth()->user()->isAdmin(); @endphp

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">{{ $isAdmin ? 'Semua Laporan' : 'Laporan Saya' }}</h1>
        <div class="flex gap-2">
            @if ($isAdmin)
                <a href="{{ route('reports.print', request()->query()) }}" target="_blank" class="bg-white border px-3 py-2 rounded text-sm hover:bg-slate-50">Cetak / PDF</a>
            @else
                <a href="{{ route('reports.create') }}" class="bg-indigo-600 text-white px-3 py-2 rounded text-sm hover:bg-indigo-700">+ Buat Laporan</a>
            @endif
        </div>
    </div>

    <form method="GET" action="{{ route('reports.index') }}" class="bg-white rounded shadow p-4 mb-4 grid md:grid-cols-4 gap-3 text-sm">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari tiket, lokasi, deskripsi{{ $isAdmin ? ', siswa' : '' }}" class="border rounded px-3 py-2 md:col-span-2">
        <select name="status" class="border rounded px-3 py-2">
            <option value="">Semua status</option>
            @foreach ($statuses as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
            @endforeach
        </select>
        <select name="category_id" class="border rounded px-3 py-2">
            <option value="">Semua kategori</option>
            @foreach ($categories as $c)
                <option value="{{ $c->id }}" @selected((string) request('category_id') === (string) $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
        <select name="urgency_id" class="border rounded px-3 py-2">
            <option value="">Semua urgensi</option>
            @foreach ($urgencies as $u)
                <option value="{{ $u->id }}" @selected((string) request('urgency_id') === (string) $u->id)>{{ $u->name }}</option>
            @endforeach
        </select>
        @if ($isAdmin)
            <select name="worker_id" class="border rounded px-3 py-2">
                <option value="">Semua petugas</option>
                @foreach ($workers as $w)
                    <option value="{{ $w->id }}" @selected((string) request('worker_id') === (string) $w->id)>{{ $w->name }}</option>
                @endforeach
            </select>
        @endif
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded px-3 py-2" title="Dari tanggal">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded px-3 py-2" title="Sampai tanggal">
        <div class="flex gap-2">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Filter</button>
            <a href="{{ route('reports.index') }}" class="px-4 py-2 rounded border hover:bg-slate-50">Reset</a>
        </div>
    </form>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 bg-slate-50">
                <tr>
                    <th class="p-3">Tiket</th>
                    @if ($isAdmin)<th>Siswa</th>@endif
                    <th>Kategori</th>
                    <th>Urgensi</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reports as $r)
                    <tr class="border-t">
                        <td class="p-3 font-medium">{{ $r->ticket_number }}</td>
                        @if ($isAdmin)<td>{{ $r->user->name }}<div class="text-xs text-slate-500">{{ $r->user->class_name }}</div></td>@endif
                        <td>{{ $r->category->name }}</td>
                        <td>{{ $r->urgency->name }}</td>
                        <td>{{ $r->location }}</td>
                        <td>@include('partials.status-badge', ['status' => $r->status])</td>
                        <td>{{ $r->created_at->format('d M Y') }}</td>
                        <td><a href="{{ route('reports.show', $r) }}" class="text-indigo-600 hover:underline">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="{{ $isAdmin ? 8 : 7 }}" class="p-6 text-center text-slate-500">Tidak ada laporan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $reports->links() }}</div>
@endsection