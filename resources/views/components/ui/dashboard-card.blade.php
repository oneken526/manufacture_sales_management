@props([
    'color'       => 'indigo',
    'badge'       => '',
    'title'       => '',
    'description' => '',
    'href'        => '#',
])

@php
$borderColor = [
    'indigo'  => 'border-indigo-500',
    'emerald' => 'border-emerald-500',
    'amber'   => 'border-amber-500',
    'violet'  => 'border-violet-500',
    'cyan'    => 'border-cyan-500',
][$color] ?? 'border-indigo-500';

$iconBg = [
    'indigo'  => 'bg-indigo-100',
    'emerald' => 'bg-emerald-100',
    'amber'   => 'bg-amber-100',
    'violet'  => 'bg-violet-100',
    'cyan'    => 'bg-cyan-100',
][$color] ?? 'bg-indigo-100';

$iconColor = [
    'indigo'  => 'text-indigo-600',
    'emerald' => 'text-emerald-600',
    'amber'   => 'text-amber-600',
    'violet'  => 'text-violet-600',
    'cyan'    => 'text-cyan-600',
][$color] ?? 'text-indigo-600';

$linkColor = [
    'indigo'  => 'text-indigo-600 hover:text-indigo-800',
    'emerald' => 'text-emerald-600 hover:text-emerald-800',
    'amber'   => 'text-amber-600 hover:text-amber-800',
    'violet'  => 'text-violet-600 hover:text-violet-800',
    'cyan'    => 'text-cyan-600 hover:text-cyan-800',
][$color] ?? 'text-indigo-600 hover:text-indigo-800';
@endphp

<div class="bg-white rounded-xl shadow-md p-6 border-l-4 {{ $borderColor }} hover:shadow-lg transition-shadow">
    <div class="flex items-center justify-between mb-3">
        <div class="w-10 h-10 {{ $iconBg }} rounded-lg flex items-center justify-center">
            <span class="{{ $iconColor }}">
                {{ $icon }}
            </span>
        </div>
        <x-badges.badge :variant="$color">{{ $badge }}</x-badges.badge>
    </div>
    <h3 class="text-base font-semibold text-slate-700 mb-1">{{ $title }}</h3>
    <p class="text-sm text-slate-400 mb-4">{{ $description }}</p>
    <a href="{{ $href }}" class="text-xs font-medium {{ $linkColor }} inline-flex items-center gap-1">
        開く
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>
