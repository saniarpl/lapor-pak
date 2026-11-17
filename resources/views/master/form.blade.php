@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded shadow p-6">
        <h1 class="text-xl font-bold mb-4">{{ $title }}</h1>

        <form method="POST" action="{{ $item ? route($routePrefix . '.update', $item) : route($routePrefix . '.store') }}" class="space-y-4">
            @csrf
            @if ($item)
                @method('PUT')
            @endif

            @foreach ($fields as $field)
                <div>
                    <label for="{{ $field['name'] }}" class="block text-sm font-medium mb-1">{{ $field['label'] }}</label>
                    @if ($field['type'] === 'textarea')
                        <textarea id="{{ $field['name'] }}" name="{{ $field['name'] }}" rows="3"
                                  @required($field['required']) class="w-full border rounded px-3 py-2">{{ old($field['name'], $item?->{$field['name']}) }}</textarea>
                    @else
                        <input id="{{ $field['name'] }}" name="{{ $field['name'] }}" type="{{ $field['type'] }}"
                               value="{{ old($field['name'], $item?->{$field['name']}) }}"
                               @required($field['required']) class="w-full border rounded px-3 py-2">
                    @endif
                </div>
            @endforeach

            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Simpan</button>
                <a href="{{ route($routePrefix . '.index') }}" class="px-4 py-2 rounded border hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
@endsection