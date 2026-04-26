@php $product ??= null; @endphp

<style>
.form-block {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}
.input-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.375rem;
}
.input-required { color: #ef4444; margin-left: 0.25rem; }
.text-field,
.select-field {
    width: 100%;
    border: 1px solid #cbd5e1;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    outline: none;
    box-sizing: border-box;
    background: #fff;
}
.text-field:focus,
.select-field:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99,102,241,.15);
}
.text-field--err,
.select-field--err { border-color: #ef4444; }
.input-error { font-size: 0.8rem; color: #ef4444; margin-top: 0.25rem; }
.input-errors {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 0.375rem;
    padding: 0.75rem 1rem;
    margin-bottom: 1.25rem;
}
.input-errors ul { margin: 0; padding-left: 1.25rem; }
.input-errors li { font-size: 0.875rem; color: #b91c1c; }
</style>

@if($errors->any())
<div class="input-errors">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="form-block">

    <div>
        <label class="input-label">
            商品コード <span class="input-required">*</span>
        </label>
        <input type="text" name="code"
               value="{{ old('code', $product?->code) }}"
               maxlength="50"
               class="text-field {{ $errors->has('code') ? 'text-field--err' : '' }}">
        @error('code')
            <p class="input-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="input-label">
            商品名 <span class="input-required">*</span>
        </label>
        <input type="text" name="name"
               value="{{ old('name', $product?->name) }}"
               maxlength="255"
               class="text-field {{ $errors->has('name') ? 'text-field--err' : '' }}">
        @error('name')
            <p class="input-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="input-label">
            カテゴリ <span class="input-required">*</span>
        </label>
        <select name="category_id"
                class="select-field {{ $errors->has('category_id') ? 'select-field--err' : '' }}">
            <option value="">選択してください</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $product?->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <p class="input-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-grid">
        <div>
            <label class="input-label">
                標準単価（円） <span class="input-required">*</span>
            </label>
            <input type="number" name="unit_price" min="0" step="1"
                   value="{{ old('unit_price', $product?->unit_price ?? 0) }}"
                   class="text-field {{ $errors->has('unit_price') ? 'text-field--err' : '' }}">
            @error('unit_price')
                <p class="input-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="input-label">単位</label>
            <input type="text" name="unit_name"
                   value="{{ old('unit_name', $product?->unit_name) }}"
                   maxlength="20"
                   placeholder="例：個・本・kg"
                   class="text-field {{ $errors->has('unit_name') ? 'text-field--err' : '' }}">
            @error('unit_name')
                <p class="input-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class="input-label">備考</label>
        <textarea name="notes" rows="3"
                  class="text-field {{ $errors->has('notes') ? 'text-field--err' : '' }}">{{ old('notes', $product?->notes) }}</textarea>
        @error('notes')
            <p class="input-error">{{ $message }}</p>
        @enderror
    </div>

</div>
