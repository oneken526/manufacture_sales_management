@props(['variant' => 'edit'])

@php
$styles = [
    'edit'   => 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100',
    'delete' => 'bg-red-50 text-red-600 hover:bg-red-100',
][$variant] ?? 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100';
$baseClass = "inline-flex items-center px-3 py-1 rounded-md text-xs font-medium transition-colors $styles";
@endphp

@if($variant === 'edit')
    <a {{ $attributes->merge(['class' => $baseClass]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $baseClass]) }}>{{ $slot }}</button>
@endif
