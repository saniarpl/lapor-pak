@extends('layouts.app')

@section('title', $student ? 'Ubah Siswa' : 'Tambah Siswa')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded shadow p-6">
        <h1 class="text-xl font-bold mb-4">{{ $student ? 'Ubah Akun Siswa' : 'Tambah Akun Siswa' }}</h1>

        <form method="POST" action="{{ $student ? route('admin.students.update', $student) : route('admin.students.store') }}" class="space-y-4">
            @csrf
            @if ($student)
                @method('PUT')
            @endif

            <div>
                <label for="name" class="block text-sm font-medium mb-1">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name', $student?->name) }}" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label for="username" class="block text-sm font-medium mb-1">Username</label>
                <input id="username" name="username" type="text" value="{{ old('username', $student?->username) }}" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label for="class_name" class="block text-sm font-medium mb-1">Kelas</label>
                <input id="class_name" name="class_name" type="text" value="{{ old('class_name', $student?->class_name) }}" required
                       placeholder="Contoh: X IPA 1" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium mb-1">
                    Password {{ $student ? '(kosongkan jika tidak diganti)' : '' }}
                </label>
                <input id="password" name="password" type="password" minlength="8" @required(! $student) class="w-full border rounded px-3 py-2">
                <p class="text-xs text-slate-500 mt-1">Minimal 8 karakter.</p>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Simpan</button>
                <a href="{{ route('admin.students.index') }}" class="px-4 py-2 rounded border hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
@endsection