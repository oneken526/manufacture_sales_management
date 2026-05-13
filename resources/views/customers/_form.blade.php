@php
    $customer ??= null;
@endphp

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

    {{-- 得意先コード --}}
    <div>
        <x-inputs.input-label value="得意先コード" :required="true" />
        <x-inputs.text-input name="code" type="text" :value="old('code', $customer?->code)" maxlength="20" class="w-full" />
        <x-inputs.input-error :messages="$errors->get('code')" />
    </div>

    {{-- 得意先名 --}}
    <div>
        <x-inputs.input-label value="得意先名" :required="true" />
        <x-inputs.text-input name="name" type="text" :value="old('name', $customer?->name)" maxlength="255" class="w-full" />
        <x-inputs.input-error :messages="$errors->get('name')" />
    </div>

    {{-- 得意先名フリガナ --}}
    <div>
        <x-inputs.input-label value="得意先名フリガナ" />
        <x-inputs.text-input name="name_kana" type="text" :value="old('name_kana', $customer?->name_kana)" maxlength="255" class="w-full" />
        <x-inputs.input-error :messages="$errors->get('name_kana')" />
    </div>

    <div class="grid grid-cols-2 gap-4">
        {{-- 郵便番号 --}}
        <div>
            <x-inputs.input-label value="郵便番号" />
            <x-inputs.text-input name="postal_code" type="text" :value="old('postal_code', $customer?->postal_code)" maxlength="10" placeholder="例：123-4567" class="w-full" />
            <x-inputs.input-error :messages="$errors->get('postal_code')" />
        </div>

        {{-- 電話番号 --}}
        <div>
            <x-inputs.input-label value="電話番号" />
            <x-inputs.text-input name="phone" type="text" :value="old('phone', $customer?->phone)" maxlength="20" placeholder="例：03-1234-5678" class="w-full" />
            <x-inputs.input-error :messages="$errors->get('phone')" />
        </div>
    </div>

    {{-- 住所 --}}
    <div>
        <x-inputs.input-label value="住所" />
        <x-inputs.textarea name="address" rows="2" class="w-full">{{ old('address', $customer?->address) }}</x-inputs.textarea>
        <x-inputs.input-error :messages="$errors->get('address')" />
    </div>

    {{-- メールアドレス --}}
    <div>
        <x-inputs.input-label value="メールアドレス" />
        <x-inputs.text-input name="email" type="email" :value="old('email', $customer?->email)" maxlength="255" class="w-full" />
        <x-inputs.input-error :messages="$errors->get('email')" />
    </div>

    <div class="grid grid-cols-2 gap-4">

        {{-- 締日 --}}
        <div>
            <x-inputs.input-label value="締日" :required="true" />
            <x-inputs.select name="closing_day" class="w-full">
                @php $selected = old('closing_day', $customer?->closing_day ?? 99) @endphp
                @foreach(range(1, 28) as $day)
                    <option value="{{ $day }}" {{ (int)$selected === $day ? 'selected' : '' }}>{{ $day }}日</option>
                @endforeach
                <option value="99" {{ (int)$selected === 99 ? 'selected' : '' }}>月末</option>
            </x-inputs.select>
            <x-inputs.input-error :messages="$errors->get('closing_day')" />
        </div>

        {{-- 与信限度額 --}}
        <div>
            <x-inputs.input-label value="与信限度額" :required="true">
                <span class="text-xs text-slate-400">（0 = 制限なし）</span>
            </x-inputs.input-label>
            <x-inputs.text-input name="credit_limit" type="number" min="0" step="1" :value="old('credit_limit', $customer?->credit_limit ?? 0)" class="w-full" />
            <x-inputs.input-error :messages="$errors->get('credit_limit')" />
        </div>

    </div>

    {{-- 備考 --}}
    <div>
        <x-inputs.input-label value="備考" />
        <x-inputs.textarea name="notes" rows="3" class="w-full">{{ old('notes', $customer?->notes) }}</x-inputs.textarea>
        <x-inputs.input-error :messages="$errors->get('notes')" />
    </div>

</div>
