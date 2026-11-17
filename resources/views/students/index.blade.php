@extends('layouts.app')

@section('title', 'Akun Siswa')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Akun Siswa</h1>
        <a href="{{ route('admin.students.create') }}" class="bg-indigo-600 text-white px-3 py-2 rounded text-sm hover:bg-indigo-700">+ Tambah Siswa</a>
    </div>

    <form method="GET" class="mb-4 flex flex-wrap gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, username, kelas" class="border rounded px-3 py-2 text-sm bg-white">
        <select name="class_name" class="border rounded px-3 py-2 text-sm bg-white">
            <option value="">Semua kelas</option>
            @foreach ($classes as $class)
                <option value="{{ $class }}" @selected(request('class_name') === $class)>{{ $class }}</option>
            @endforeach
        </select>
        <button class="bg-slate-700 text-white px-4 py-2 rounded text-sm">Cari</button>
        <a href="{{ route('admin.students.index') }}" class="px-4 py-2 rounded border text-sm bg-white">Reset</a>
    </form>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 bg-slate-50">
                <tr><th class="p-3">Nama</th><th>Username</th><th>Kelas</th><th>Laporan</th><th class="w-40">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($students as $s)
                    <tr class="border-t">
                        <td class="p-3">{{ $s->name }}</td>
                        <td>{{ $s->username }}</td>
                        <td>{{ $s->class_name }}</td>
                        <td>{{ $s->reports_count }}</td>
                        <td>
                            <a href="{{ route('admin.students.edit', $s) }}" class="text-indigo-600 hover:underline mr-3">Ubah</a>
                            <form method="POST" action="{{ route('admin.students.destroy', $s) }}" class="inline"
                                  onsubmit="return confirm('Hapus akun siswa ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-slate-500">Belum ada siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $students->links() }}</div>
@endsection