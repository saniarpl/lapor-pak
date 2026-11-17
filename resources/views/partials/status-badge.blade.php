@php
    $classes = match ($status) {
        \App\Models\Report::STATUS_PENDING  => 'bg-yellow-100 text-yellow-800',
        \App\Models\Report::STATUS_ACCEPTED => 'bg-blue-100 text-blue-800',
        \App\Models\Report::STATUS_PROGRESS => 'bg-indigo-100 text-indigo-800',
        \App\Models\Report::STATUS_DONE     => 'bg-green-100 text-green-800',
        \App\Models\Report::STATUS_REJECTED => 'bg-red-100 text-red-800',
        default                             => 'bg-gray-100 text-gray-800',
    };
@endphp
<span class="inline-block px-2 py-0.5 rounded text-xs font-medium {{ $classes }}">{{ $status }}</span>