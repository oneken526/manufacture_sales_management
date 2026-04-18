@php
    $customer ??= null;
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

    {{-- 得意先コード --}}
    <div>
        <label class="form-label">
            得意先コード <span class="form-required">*</span>
        </label>
        <input type="text" name="code"
               value="{{ old('code', $customer?->code) }}"
               maxlength="20"
               class="form-control {{ $errors->has('code') ? 'form-control--error' : '' }}">
        @error('code')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    {{-- 得意先名 --}}
    <div>
        <label class="form-label">
            得意先名 <span class="form-required">*</span>
        </label>
        <input type="text" name="name"
               value="{{ old('name', $customer?->name) }}"
               maxlength="255"
               class="form-control {{ $errors->has('name') ? 'form-control--error' : '' }}">
        @error('name')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    {{-- 得意先名フリガナ --}}
    <div>
        <label class="form-label">得意先名フリガナ</label>
        <input type="text" name="name_kana"
               value="{{ old('name_kana', $customer?->name_kana) }}"
               maxlength="255"
               class="form-control {{ $errors->has('name_kana') ? 'form-control--error' : '' }}">
        @error('name_kana')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-row-2">
        {{-- 郵便番号 --}}
        <div>
            <label class="form-label">郵便番号</label>
            <input type="text" name="postal_code"
                   value="{{ old('postal_code', $customer?->postal_code) }}"
                   maxlength="10"
                   placeholder="例：123-4567"
                   class="form-control {{ $errors->has('postal_code') ? 'form-control--error' : '' }}">
            @error('postal_code')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 電話番号 --}}
        <div>
            <label class="form-label">電話番号</label>
            <input type="text" name="phone"
                   value="{{ old('phone', $customer?->phone) }}"
                   maxlength="20"
                   placeholder="例：03-1234-5678"
                   class="form-control {{ $errors->has('phone') ? 'form-control--error' : '' }}">
            @error('phone')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- 住所 --}}
    <div>
        <label class="form-label">住所</label>
        <textarea name="address" rows="2"
                  class="form-control {{ $errors->has('address') ? 'form-control--error' : '' }}">{{ old('address', $customer?->address) }}</textarea>
        @error('address')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    {{-- メールアドレス --}}
    <div>
        <label class="form-label">メールアドレス</label>
        <input type="email" name="email"
               value="{{ old('email', $customer?->email) }}"
               maxlength="255"
               class="form-control {{ $errors->has('email') ? 'form-control--error' : '' }}">
        @error('email')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-row-2">

        {{-- 締日 --}}
        <div>
            <label class="form-label">
                締日 <span class="form-required">*</span>
            </label>
            <select name="closing_day"
                    class="form-control {{ $errors->has('closing_day') ? 'form-control--error' : '' }}">
                @php $selected = old('closing_day', $customer?->closing_day ?? 99) @endphp
                @foreach(range(1, 28) as $day)
                    <option value="{{ $day }}" {{ (int)$selected === $day ? 'selected' : '' }}>{{ $day }}日</option>
                @endforeach
                <option value="99" {{ (int)$selected === 99 ? 'selected' : '' }}>月末</option>
            </select>
            @error('closing_day')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 与信限度額 --}}
        <div>
            <label class="form-label">
                与信限度額 <span class="form-required">*</span>
                <span class="form-hint">（0 = 制限なし）</span>
            </label>
            <input type="number" name="credit_limit" min="0" step="1"
                   value="{{ old('credit_limit', $customer?->credit_limit ?? 0) }}"
                   class="form-control {{ $errors->has('credit_limit') ? 'form-control--error' : '' }}">
            @error('credit_limit')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

    </div>

    {{-- 備考 --}}
    <div>
        <label class="form-label">備考</label>
        <textarea name="notes" rows="3"
                  class="form-control {{ $errors->has('notes') ? 'form-control--error' : '' }}">{{ old('notes', $customer?->notes) }}</textarea>
        @error('notes')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

</div>
