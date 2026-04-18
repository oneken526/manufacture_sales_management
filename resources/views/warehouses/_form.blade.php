@php
    $warehouse ??= null;
@endphp

@if($errors->any())
<div class="form-errors">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="form-stack">

    {{-- 倉庫コード --}}
    <div>
        <label class="form-label">
            倉庫コード <span class="form-required">*</span>
        </label>
        <input type="text" name="code"
               value="{{ old('code', $warehouse?->code) }}"
               maxlength="50"
               class="form-control {{ $errors->has('code') ? 'form-control--error' : '' }}">
        @error('code')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    {{-- 倉庫名 --}}
    <div>
        <label class="form-label">
            倉庫名 <span class="form-required">*</span>
        </label>
        <input type="text" name="name"
               value="{{ old('name', $warehouse?->name) }}"
               maxlength="100"
               class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}">
        @error('name')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

</div>
