@props(['variant' => 'primary'])

@php
$styles = [
    'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm',
    'ghost'   => 'bg-slate-100 text-slate-600 hover:bg-slate-200',
][$variant] ?? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm';
@endphp

<a {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-lg transition-colors $styles"]) }}>
    {{ $slot }}
</a>
