@php
    $customer ??= null;
@endphp

@if($errors->any())
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded text-sm text-red-700">
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
        <label class="block text-sm font-medium text-gray-700 mb-1">
            得意先コード <span class="text-red-500">*</span>
        </label>
        <input type="text" name="code"
               value="{{ old('code', $customer?->code) }}"
               maxlength="20"
               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('code') ? 'border-red-400' : 'border-gray-300' }}">
        @error('code')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- 得意先名 --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            得意先名 <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name"
               value="{{ old('name', $customer?->name) }}"
               maxlength="255"
               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('name') ? 'border-red-400' : 'border-gray-300' }}">
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- 得意先名フリガナ --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">得意先名フリガナ</label>
        <input type="text" name="name_kana"
               value="{{ old('name_kana', $customer?->name_kana) }}"
               maxlength="255"
               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('name_kana') ? 'border-red-400' : 'border-gray-300' }}">
        @error('name_kana')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        {{-- 郵便番号 --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">郵便番号</label>
            <input type="text" name="postal_code"
                   value="{{ old('postal_code', $customer?->postal_code) }}"
                   maxlength="10"
                   placeholder="例：123-4567"
                   class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('postal_code') ? 'border-red-400' : 'border-gray-300' }}">
            @error('postal_code')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- 電話番号 --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">電話番号</label>
            <input type="text" name="phone"
                   value="{{ old('phone', $customer?->phone) }}"
                   maxlength="20"
                   placeholder="例：03-1234-5678"
                   class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('phone') ? 'border-red-400' : 'border-gray-300' }}">
            @error('phone')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- 住所 --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">住所</label>
        <textarea name="address" rows="2"
                  class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('address') ? 'border-red-400' : 'border-gray-300' }}">{{ old('address', $customer?->address) }}</textarea>
        @error('address')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- メールアドレス --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
        <input type="email" name="email"
               value="{{ old('email', $customer?->email) }}"
               maxlength="255"
               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }}">
        @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">

        {{-- 締日 --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                締日 <span class="text-red-500">*</span>
            </label>
            <select name="closing_day"
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('closing_day') ? 'border-red-400' : 'border-gray-300' }}">
                @php $selected = old('closing_day', $customer?->closing_day ?? 99) @endphp
                @foreach(range(1, 28) as $day)
                    <option value="{{ $day }}" {{ (int)$selected === $day ? 'selected' : '' }}>{{ $day }}日</option>
                @endforeach
                <option value="99" {{ (int)$selected === 99 ? 'selected' : '' }}>月末</option>
            </select>
            @error('closing_day')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- 与信限度額 --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                与信限度額 <span class="text-red-500">*</span>
                <span class="text-xs text-gray-400">（0 = 制限なし）</span>
            </label>
            <input type="number" name="credit_limit" min="0" step="1"
                   value="{{ old('credit_limit', $customer?->credit_limit ?? 0) }}"
                   class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('credit_limit') ? 'border-red-400' : 'border-gray-300' }}">
            @error('credit_limit')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

    </div>

    {{-- 備考 --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">備考</label>
        <textarea name="notes" rows="3"
                  class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('notes') ? 'border-red-400' : 'border-gray-300' }}">{{ old('notes', $customer?->notes) }}</textarea>
        @error('notes')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

</div>
