@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
    @php $isAdmin = auth()->user()->isAdmin(); @endphp

    <div class="mb-4">
        <a href="{{ route('reports.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke daftar</a>
    </div>

    <div class="grid lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded shadow p-5">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h1 class="text-xl font-bold">{{ $report->ticket_number }}</h1>
                        <p class="text-sm text-slate-500">Dibuat {{ $report->created_at->format('d M Y H:i') }}</p>
                    </div>
                    @include('partials.status-badge', ['status' => $report->status])
                </div>

                <dl class="grid grid-cols-3 gap-y-2 text-sm">
                    <dt class="text-slate-500">Pelapor</dt><dd class="col-span-2">{{ $report->user->name }} ({{ $report->user->class_name }})</dd>
                    <dt class="text-slate-500">Kategori</dt><dd class="col-span-2">{{ $report->category->name }}</dd>
                    <dt class="text-slate-500">Urgensi</dt><dd class="col-span-2">{{ $report->urgency->name }}</dd>
                    <dt class="text-slate-500">Lokasi</dt><dd class="col-span-2">{{ $report->location }}</dd>
                    <dt class="text-slate-500">Deskripsi</dt><dd class="col-span-2 whitespace-pre-line">{{ $report->description }}</dd>
                    <dt class="text-slate-500">Petugas</dt><dd class="col-span-2">{{ $report->worker?->name ?? 'Belum ditugaskan' }}</dd>
                    @if ($report->rejection_reason)
                        <dt class="text-slate-500">Alasan Penolakan</dt><dd class="col-span-2 text-red-700 whitespace-pre-line">{{ $report->rejection_reason }}</dd>
                    @endif
                    @if ($report->repair_note)
                        <dt class="text-slate-500">Catatan Perbaikan</dt><dd class="col-span-2 whitespace-pre-line">{{ $report->repair_note }}</dd>
                    @endif
                </dl>

                @if ($report->photoUrl())
                    <div class="mt-4">
                        <img src="{{ $report->photoUrl() }}" alt="Foto kerusakan" class="max-h-80 rounded border">
                    </div>
                @endif
            </div>

            <div class="bg-white rounded shadow p-5">
                <h2 class="font-semibold mb-3">Riwayat Status</h2>
                <ol class="border-l-2 border-indigo-200 ml-2 space-y-4">
                    @foreach ($report->histories as $h)
                        <li class="ml-4">
                            <div class="text-sm font-medium">
                                @if ($h->old_status && $h->old_status !== $h->new_status)
                                    {{ $h->old_status }} &rarr; {{ $h->new_status }}
                                @else
                                    {{ $h->new_status }}
                                @endif
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $h->created_at->format('d M Y H:i') }}
                                @if ($h->admin) &middot; oleh {{ $h->admin->name }} @endif
                            </div>
                            @if ($h->note)<div class="text-sm mt-1 whitespace-pre-line">{{ $h->note }}</div>@endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>

        @if ($isAdmin)
            <div class="space-y-4 no-print">
                {{-- Perubahan status sesuai alur --}}
                <div class="bg-white rounded shadow p-5">
                    <h2 class="font-semibold mb-3">Ubah Status</h2>
                    @forelse ($report->allowedTransitions() as $next)
                        <form method="POST" action="{{ route('reports.status', $report) }}" class="mb-4 pb-4 border-b last:border-0 space-y-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="new_status" value="{{ $next }}">

                            @if ($next === \App\Models\Report::STATUS_REJECTED)
                                <label class="block text-sm font-medium">Alasan penolakan (wajib)</label>
                                <textarea name="rejection_reason" rows="3" required class="w-full border rounded px-3 py-2 text-sm"></textarea>
                                <button class="bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700">Tolak Laporan</button>
                            @elseif ($next === \App\Models\Report::STATUS_DONE)
                                <label class="block text-sm font-medium">Catatan perbaikan (opsional)</label>
                                <textarea name="repair_note" rows="3" class="w-full border rounded px-3 py-2 text-sm">{{ $report->repair_note }}</textarea>
                                <button class="bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700">Tandai Selesai</button>
                            @else
                                <label class="block text-sm font-medium">Catatan (opsional)</label>
                                <input type="text" name="note" class="w-full border rounded px-3 py-2 text-sm">
                                <button class="bg-indigo-600 text-white px-3 py-2 rounded text-sm hover:bg-indigo-700">
                                    {{ $next === \App\Models\Report::STATUS_ACCEPTED ? 'Verifikasi / Terima' : 'Mulai Perbaikan' }}
                                </button>
                            @endif
                        </form>
                    @empty
                        <p class="text-sm text-slate-500">Status sudah final, tidak dapat diubah lagi.</p>
                    @endforelse
                </div>

                {{-- Petugas + WhatsApp --}}
                <div class="bg-white rounded shadow p-5">
                    <h2 class="font-semibold mb-3">Petugas</h2>
                    <form method="POST" action="{{ route('reports.worker', $report) }}" class="space-y-2">
                        @csrf
                        @method('PATCH')
                        <select name="worker_id" required class="w-full border rounded px-3 py-2 text-sm">
                            <option value="">Pilih petugas</option>
                            @foreach ($workers as $w)
                                <option value="{{ $w->id }}" @selected($report->worker_id === $w->id)>{{ $w->name }}</option>
                            @endforeach
                        </select>
                        <button class="bg-slate-700 text-white px-3 py-2 rounded text-sm hover:bg-slate-800">Simpan Petugas</button>
                    </form>
                    @if ($report->whatsappUrl())
                        <a href="{{ $report->whatsappUrl() }}" target="_blank" rel="noopener"
                           class="mt-3 inline-block bg-green-500 text-white px-3 py-2 rounded text-sm hover:bg-green-600">Bagikan via WhatsApp</a>
                    @endif
                </div>

                {{-- Catatan perbaikan --}}
                <div class="bg-white rounded shadow p-5">
                    <h2 class="font-semibold mb-3">Catatan Perbaikan</h2>
                    <form method="POST" action="{{ route('reports.note', $report) }}" class="space-y-2">
                        @csrf
                        @method('PATCH')
                        <textarea name="repair_note" rows="3" required class="w-full border rounded px-3 py-2 text-sm">{{ old('repair_note', $report->repair_note) }}</textarea>
                        <button class="bg-slate-700 text-white px-3 py-2 rounded text-sm hover:bg-slate-800">Simpan Catatan</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection