@extends('layouts.app')

@section('title', 'Buat Laporan')

@section('content')
    <div class="max-w-2xl mx-auto bg-white rounded shadow p-6">
        <h1 class="text-xl font-bold mb-4">Buat Laporan Kerusakan</h1>

        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="category_id" class="block text-sm font-medium mb-1">Kategori</label>
                    <select id="category_id" name="category_id" required class="w-full border rounded px-3 py-2">
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected((string) old('category_id') === (string) $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="urgency_id" class="block text-sm font-medium mb-1">Tingkat Urgensi</label>
                    <select id="urgency_id" name="urgency_id" required class="w-full border rounded px-3 py-2">
                        <option value="">Pilih urgensi</option>
                        @foreach ($urgencies as $u)
                            <option value="{{ $u->id }}" @selected((string) old('urgency_id') === (string) $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="location" class="block text-sm font-medium mb-1">Lokasi / Ruangan</label>
                <input id="location" name="location" type="text" value="{{ old('location') }}" required maxlength="255"
                       placeholder="Contoh: Ruang X IPA 1, Toilet lantai 2" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1">Deskripsi Kerusakan</label>
                <textarea id="description" name="description" rows="4" required maxlength="2000"
                          class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="photo" class="block text-sm font-medium mb-1">Foto Kerusakan (JPG/PNG/WEBP, maks. 2 MB)</label>
                <input id="photo" name="photo" type="file" accept="image/*" required class="w-full text-sm">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Kirim Laporan</button>
                <a href="{{ route('reports.index') }}" class="px-4 py-2 rounded border hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
@endsection