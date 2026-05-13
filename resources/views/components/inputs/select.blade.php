@php
$name = $attributes->get('name');
$hasError = $name ? $errors->has($name) : false;
$borderColor = $hasError ? 'border-red-400' : 'border-slate-300';
$baseClass = "border $borderColor rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent";
@endphp

<select {{ $attributes->merge(['class' => $baseClass]) }}>
    {{ $slot }}
</select>
