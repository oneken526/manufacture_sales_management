@php $productCategory ??= null; @endphp

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
    <div>
        <label class="form-label">
            カテゴリ名 <span class="form-required">*</span>
        </label>
        <input type="text" name="name"
               value="{{ old('name', $productCategory?->name) }}"
               maxlength="100"
               class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}">
        @error('name')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>
</div>
