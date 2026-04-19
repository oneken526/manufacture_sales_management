<style>
.user-form-stack {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}
.user-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}
.field-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.375rem;
}
.field-required {
    color: #ef4444;
    margin-left: 0.25rem;
}
.field-hint {
    display: block;
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: 0.25rem;
}
.field-input {
    width: 100%;
    border: 1px solid #cbd5e1;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    outline: none;
    box-sizing: border-box;
}
.field-input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99,102,241,.15);
}
.field-input--invalid {
    border-color: #ef4444;
}
.field-select {
    width: 100%;
    border: 1px solid #cbd5e1;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    outline: none;
    background: #fff;
    box-sizing: border-box;
}
.field-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99,102,241,.15);
}
.field-select--invalid { border-color: #ef4444; }
.field-errors {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 0.375rem;
    padding: 0.75rem 1rem;
    margin-bottom: 1.25rem;
}
.field-errors ul { margin: 0; padding-left: 1.25rem; }
.field-errors li { font-size: 0.875rem; color: #b91c1c; }
.field-error-msg { font-size: 0.8rem; color: #ef4444; margin-top: 0.25rem; }
.user-form-actions {
    display: flex;
    gap: 0.75rem;
    padding-top: 0.5rem;
    border-top: 1px solid #f1f5f9;
    margin-top: 0.5rem;
}
</style>

@if($errors->any())
    <div class="field-errors">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="user-form-stack">
    <div>
        <label class="field-label" for="name">名前<span class="field-required">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}"
               class="field-input {{ $errors->has('name') ? 'field-input--invalid' : '' }}"
               maxlength="255" required>
        @error('name')
            <p class="field-error-msg">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="email">メールアドレス<span class="field-required">*</span></label>
        <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}"
               class="field-input {{ $errors->has('email') ? 'field-input--invalid' : '' }}"
               maxlength="255" required>
        @error('email')
            <p class="field-error-msg">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="role">ロール<span class="field-required">*</span></label>
        <select id="role" name="role"
                class="field-select {{ $errors->has('role') ? 'field-select--invalid' : '' }}">
            <option value="">選択してください</option>
            @foreach(['admin' => '管理者', 'sales' => '営業', 'manufacture' => '製造', 'warehouse' => '倉庫'] as $value => $label)
                <option value="{{ $value }}"
                        {{ old('role', $user->roles->first()?->name ?? '') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('role')
            <p class="field-error-msg">{{ $message }}</p>
        @enderror
    </div>

    <div class="user-form-row">
        <div>
            <label class="field-label" for="password">
                パスワード{{ isset($user) ? '' : '' }}<span class="field-required">{{ isset($user) ? '' : '*' }}</span>
            </label>
            @if(isset($user))
                <span class="field-hint">変更する場合のみ入力してください</span>
            @endif
            <input type="password" id="password" name="password"
                   class="field-input {{ $errors->has('password') ? 'field-input--invalid' : '' }}"
                   {{ isset($user) ? '' : 'required' }}>
            @error('password')
                <p class="field-error-msg">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="field-label" for="password_confirmation">
                パスワード（確認）<span class="field-required">{{ isset($user) ? '' : '*' }}</span>
            </label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="field-input"
                   {{ isset($user) ? '' : 'required' }}>
        </div>
    </div>
</div>
