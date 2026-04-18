@php $product ??= null; @endphp

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

    {{-- 商品コード --}}
    <div>
        <label class="form-label">
            商品コード <span class="form-required">*</span>
        </label>
        <input type="text" name="code"
               value="{{ old('code', $product?->code) }}"
               maxlength="50"
               class="form-control {{ $errors->has('code') ? 'form-control--error' : '' }}">
        @error('code')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    {{-- 商品名 --}}
    <div>
        <label class="form-label">
            商品名 <span class="form-required">*</span>
        </label>
        <input type="text" name="name"
               value="{{ old('name', $product?->name) }}"
               maxlength="255"
               class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}">
        @error('name')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    {{-- カテゴリ --}}
    <div>
        <label class="form-label">
            カテゴリ <span class="form-required">*</span>
        </label>
        <select name="category_id"
                class="form-control {{ $errors->has('category_id') ? 'form-control--error' : '' }}">
            <option value="">選択してください</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $product?->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-row-2">
        {{-- 標準単価 --}}
        <div>
            <label class="form-label">
                標準単価（円） <span class="form-required">*</span>
            </label>
            <input type="number" name="unit_price" min="0" step="1"
                   value="{{ old('unit_price', $product?->unit_price ?? 0) }}"
                   class="form-control {{ $errors->has('unit_price') ? 'form-control--error' : '' }}">
            @error('unit_price')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 単位 --}}
        <div>
            <label class="form-label">単位</label>
            <input type="text" name="unit_name"
                   value="{{ old('unit_name', $product?->unit_name) }}"
                   maxlength="20"
                   placeholder="例：個・本・kg"
                   class="form-control {{ $errors->has('unit_name') ? 'form-control--error' : '' }}">
            @error('unit_name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- 備考 --}}
    <div>
        <label class="form-label">備考</label>
        <textarea name="notes" rows="3"
                  class="form-control {{ $errors->has('notes') ? 'form-control--error' : '' }}">{{ old('notes', $product?->notes) }}</textarea>
        @error('notes')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

</div>
