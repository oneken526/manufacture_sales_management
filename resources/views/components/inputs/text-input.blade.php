@props(['disabled' => false, 'variant' => 'default'])

@php
$name = $attributes->get('name');
$hasError = $name ? $errors->has($name) : false;

if ($variant === 'search' || $name) {
    $borderColor = $hasError ? 'border-red-400' : 'border-slate-300';
    $baseClass = "border $borderColor rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent";
} else {
    $baseClass = 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm';
}
@endphp

<input @disabled($disabled) {{ $attributes->merge(['class' => $baseClass]) }}>
