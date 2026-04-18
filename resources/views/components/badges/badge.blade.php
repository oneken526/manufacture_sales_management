@props(['variant' => 'indigo'])

@php
$styles = [
    'indigo'  => 'text-indigo-600 bg-indigo-50',
    'emerald' => 'text-emerald-600 bg-emerald-50',
    'amber'   => 'text-amber-600 bg-amber-50',
    'violet'  => 'text-violet-600 bg-violet-50',
    'cyan'    => 'text-cyan-600 bg-cyan-50',
    'default' => 'text-slate-600 bg-slate-100',
    'success' => 'text-green-600 bg-green-50',
    'warning' => 'text-yellow-600 bg-yellow-50',
    'danger'  => 'text-red-600 bg-red-50',
    'info'    => 'text-blue-600 bg-blue-50',
][$variant] ?? 'text-slate-600 bg-slate-100';
@endphp

<span {{ $attributes->merge(['class' => "text-xs font-semibold $styles px-2 py-0.5 rounded-full"]) }}>
    {{ $slot }}
</span>
