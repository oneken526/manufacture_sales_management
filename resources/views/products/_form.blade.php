@php $product ??= null; @endphp

@if($errors->any())
<div class="mb-4 flex items-start gap-3 bg-red-50 border-l-4 border-red-400 rounded-lg px-4 py-3 text-sm text-red-700">
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 gap-4">

    {{-- 商品コード --}}
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">
            商品コード <span class="text-red-500">*</span>
        </label>
        <input type="text" name="code"
               value="{{ old('code', $product?->code) }}"
               maxlength="50"
               class="w-full border rounded-lgpx-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('code') ? 'border-red-400' : 'border-slate-300' }}">
        @error('code')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- 商品名 --}}
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">
            商品名 <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name"
               value="{{ old('name', $product?->name) }}"
               maxlength="255"
               class="w-full border rounded-lgpx-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('name') ? 'border-red-400' : 'border-slate-300' }}">
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- カテゴリ --}}
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">
            カテゴリ <span class="text-red-500">*</span>
        </label>
        <select name="category_id"
                class="w-full border rounded-lgpx-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('category_id') ? 'border-red-400' : 'border-slate-300' }}">
            <option value="">選択してください</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $product?->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        {{-- 標準単価 --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                標準単価（円） <span class="text-red-500">*</span>
            </label>
            <input type="number" name="unit_price" min="0" step="1"
                   value="{{ old('unit_price', $product?->unit_price ?? 0) }}"
                   class="w-full border rounded-lgpx-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('unit_price') ? 'border-red-400' : 'border-slate-300' }}">
            @error('unit_price')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- 単位 --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">単位</label>
            <input type="text" name="unit_name"
                   value="{{ old('unit_name', $product?->unit_name) }}"
                   maxlength="20"
                   placeholder="例：個・本・kg"
                   class="w-full border rounded-lgpx-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('unit_name') ? 'border-red-400' : 'border-slate-300' }}">
            @error('unit_name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- 備考 --}}
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">備考</label>
        <textarea name="notes" rows="3"
                  class="w-full border rounded-lgpx-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('notes') ? 'border-red-400' : 'border-slate-300' }}">{{ old('notes', $product?->notes) }}</textarea>
        @error('notes')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

</div>
