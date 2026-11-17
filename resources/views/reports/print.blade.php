<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kerusakan Fasilitas Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 landscape; margin: 12mm; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body class="bg-white text-slate-900 p-6 text-sm">
    <div class="no-print mb-4 flex gap-2">
        <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded">Cetak / Simpan PDF</button>
        <a href="{{ route('reports.index', $filters) }}" class="px-4 py-2 rounded border">Kembali</a>
        <span class="text-slate-500 self-center">Pilih "Save as PDF" pada dialog cetak browser untuk ekspor PDF.</span>
    </div>

    <h1 class="text-xl font-bold">Laporan Kerusakan Fasilitas Sekolah</h1>
    <p class="text-slate-600 mb-1">Dicetak: {{ now()->format('d M Y H:i') }} &middot; Total: {{ $reports->count() }} laporan</p>
    @if (collect($filters)->filter()->isNotEmpty())
        <p class="text-slate-600 mb-3">
            Filter:
            @foreach (collect($filters)->filter() as $key => $value)
                {{ $key }} = {{ $value }}@if (! $loop->last), @endif
            @endforeach
        </p>
    @endif

    @php $byStatus = $reports->groupBy('status')->map->count(); @endphp
    <p class="mb-3">
        @foreach (\App\Models\Report::statuses() as $s)
            <span class="mr-4">{{ $s }}: <strong>{{ $byStatus[$s] ?? 0 }}</strong></span>
        @endforeach
    </p>

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-slate-100 text-left">
                <th class="border p-2">No</th>
                <th class="border p-2">Tiket</th>
                <th class="border p-2">Tanggal</th>
                <th class="border p-2">Siswa</th>
                <th class="border p-2">Kategori</th>
                <th class="border p-2">Urgensi</th>
                <th class="border p-2">Lokasi</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Petugas</th>
                <th class="border p-2">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $r)
                <tr>
                    <td class="border p-2">{{ $loop->iteration }}</td>
                    <td class="border p-2">{{ $r->ticket_number }}</td>
                    <td class="border p-2">{{ $r->created_at->format('d/m/Y') }}</td>
                    <td class="border p-2">{{ $r->user->name }} ({{ $r->user->class_name }})</td>
                    <td class="border p-2">{{ $r->category->name }}</td>
                    <td class="border p-2">{{ $r->urgency->name }}</td>
                    <td class="border p-2">{{ $r->location }}</td>
                    <td class="border p-2">{{ $r->status }}</td>
                    <td class="border p-2">{{ $r->worker?->name ?? '-' }}</td>
                    <td class="border p-2">{{ $r->rejection_reason ?? $r->repair_note ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="10" class="border p-4 text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>