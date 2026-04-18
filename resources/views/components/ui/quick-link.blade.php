@props(['href' => '#'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex items-center gap-2 px-4 py-3 bg-slate-50 hover:bg-indigo-50 rounded-lg text-sm text-slate-600 hover:text-indigo-700 transition-colors']) }}>
    @if ($icon->isNotEmpty())
        {{ $icon }}
    @endif
    {{ $slot }}
</a>
