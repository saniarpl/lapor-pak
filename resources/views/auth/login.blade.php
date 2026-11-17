@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <div class="max-w-sm mx-auto mt-16 bg-white rounded-lg shadow p-6">
        <h1 class="text-xl font-bold text-center mb-1">Sistem Laporan Kerusakan</h1>
        <p class="text-sm text-slate-500 text-center mb-6">Fasilitas Sekolah. Masuk dengan akun dari Admin.</p>

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf
            <div>
                <label for="username" class="block text-sm font-medium mb-1">Username</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium mb-1">Password</label>
                <input id="password" name="password" type="password" required
                       class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1"> Ingat saya
            </label>
            <button type="submit" class="w-full bg-indigo-600 text-white rounded py-2 hover:bg-indigo-700">Masuk</button>
        </form>
    </div>
@endsection