@props(['messages'])

@if ($messages)
    @foreach ((array) $messages as $message)
        <p {{ $attributes->merge(['class' => 'text-red-500 text-xs mt-1']) }}>{{ $message }}</p>
    @endforeach
@endif
