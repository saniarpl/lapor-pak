@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">{{ $title }}</h1>
        <a href="{{ route($routePrefix . '.create') }}" class="bg-indigo-600 text-white px-3 py-2 rounded text-sm hover:bg-indigo-700">+ Tambah</a>
    </div>

    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari..." class="border rounded px-3 py-2 text-sm bg-white">
        <button class="bg-slate-700 text-white px-4 py-2 rounded text-sm">Cari</button>
        <a href="{{ route($routePrefix . '.index') }}" class="px-4 py-2 rounded border text-sm bg-white">Reset</a>
    </form>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 bg-slate-50">
                <tr>
                    @foreach ($columns as $label)
                        <th class="p-3">{{ $label }}</th>
                    @endforeach
                    <th class="p-3 w-40">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr class="border-t">
                        @foreach ($columns as $key => $label)
                            <td class="p-3">{{ $item->{$key} }}</td>
                        @endforeach
                        <td class="p-3">
                            <a href="{{ route($routePrefix . '.edit', $item) }}" class="text-indigo-600 hover:underline mr-3">Ubah</a>
                            <form method="POST" action="{{ route($routePrefix . '.destroy', $item) }}" class="inline"
                                  onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="{{ count($columns) + 1 }}" class="p-6 text-center text-slate-500">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
@endsection