@extends('layouts.app')

@section('title', 'Riwayat Status')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Riwayat Perubahan Status</h1>

    <form method="GET" class="mb-4 flex flex-wrap gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari tiket atau catatan" class="border rounded px-3 py-2 text-sm bg-white">
        <select name="status" class="border rounded px-3 py-2 text-sm bg-white">
            <option value="">Semua status</option>
            @foreach ($statuses as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
            @endforeach
        </select>
        <button class="bg-slate-700 text-white px-4 py-2 rounded text-sm">Filter</button>
        <a href="{{ route('admin.histories.index') }}" class="px-4 py-2 rounded border text-sm bg-white">Reset</a>
    </form>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 bg-slate-50">
                <tr><th class="p-3">Waktu</th><th>Tiket</th><th>Perubahan</th><th>Oleh</th><th>Catatan</th></tr>
            </thead>
            <tbody>
                @forelse ($histories as $h)
                    <tr class="border-t align-top">
                        <td class="p-3 whitespace-nowrap">{{ $h->created_at->format('d M Y H:i') }}</td>
                        <td>
                            @if ($h->report)
                                <a href="{{ route('reports.show', $h->report) }}" class="text-indigo-600 hover:underline">{{ $h->report->ticket_number }}</a>
                            @else - @endif
                        </td>
                        <td>
                            @if ($h->old_status && $h->old_status !== $h->new_status)
                                {{ $h->old_status }} &rarr; {{ $h->new_status }}
                            @else
                                {{ $h->new_status }}
                            @endif
                        </td>
                        <td>{{ $h->admin?->name ?? 'Siswa/Sistem' }}</td>
                        <td class="whitespace-pre-line">{{ $h->note }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-slate-500">Belum ada riwayat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $histories->links() }}</div>
@endsection