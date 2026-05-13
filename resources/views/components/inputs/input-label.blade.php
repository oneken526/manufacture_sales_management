@props(['value' => '', 'required' => false])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-slate-700 mb-1']) }}>
    {{ $value }}
    @if($required)
        <span class="text-red-500">*</span>
    @endif
    {{ $slot }}
</label>
