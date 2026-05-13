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
        <x-inputs.input-label value="商品コード" :required="true" />
        <x-inputs.text-input name="code" type="text" :value="old('code', $product?->code)" maxlength="50" class="w-full" />
        <x-inputs.input-error :messages="$errors->get('code')" />
    </div>

    {{-- 商品名 --}}
    <div>
        <x-inputs.input-label value="商品名" :required="true" />
        <x-inputs.text-input name="name" type="text" :value="old('name', $product?->name)" maxlength="255" class="w-full" />
        <x-inputs.input-error :messages="$errors->get('name')" />
    </div>

    {{-- カテゴリ --}}
    <div>
        <x-inputs.input-label value="カテゴリ" :required="true" />
        <x-inputs.select name="category_id" class="w-full">
            <option value="">選択してください</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $product?->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </x-inputs.select>
        <x-inputs.input-error :messages="$errors->get('category_id')" />
    </div>

    <div class="grid grid-cols-2 gap-4">
        {{-- 標準単価 --}}
        <div>
            <x-inputs.input-label value="標準単価（円）" :required="true" />
            <x-inputs.text-input name="unit_price" type="number" min="0" step="1" :value="old('unit_price', $product?->unit_price ?? 0)" class="w-full" />
            <x-inputs.input-error :messages="$errors->get('unit_price')" />
        </div>

        {{-- 単位 --}}
        <div>
            <x-inputs.input-label value="単位" />
            <x-inputs.text-input name="unit_name" type="text" :value="old('unit_name', $product?->unit_name)" maxlength="20" placeholder="例：個・本・kg" class="w-full" />
            <x-inputs.input-error :messages="$errors->get('unit_name')" />
        </div>
    </div>

    {{-- 備考 --}}
    <div>
        <x-inputs.input-label value="備考" />
        <x-inputs.textarea name="notes" rows="3" class="w-full">{{ old('notes', $product?->notes) }}</x-inputs.textarea>
        <x-inputs.input-error :messages="$errors->get('notes')" />
    </div>

</div>
