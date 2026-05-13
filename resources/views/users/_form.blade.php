@php
    $user ??= null;
    $isCreate = $user === null;
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

    {{-- ユーザー名 --}}
    <div>
        <x-inputs.input-label value="ユーザー名" :required="true" />
        <x-inputs.text-input name="name" type="text" :value="old('name', $user?->name)" maxlength="255" class="w-full" />
        <x-inputs.input-error :messages="$errors->get('name')" />
    </div>

    {{-- メールアドレス --}}
    <div>
        <x-inputs.input-label value="メールアドレス" :required="true" />
        <x-inputs.text-input name="email" type="email" :value="old('email', $user?->email)" maxlength="255" class="w-full" />
        <x-inputs.input-error :messages="$errors->get('email')" />
    </div>

    {{-- パスワード --}}
    <div>
        <x-inputs.input-label value="パスワード" :required="$isCreate" />
        @if(!$isCreate)
            <p class="text-xs text-slate-400 mb-1">変更しない場合は空白のままにしてください</p>
        @endif
        <x-inputs.text-input name="password" type="password" value="" autocomplete="new-password" class="w-full" />
        <x-inputs.input-error :messages="$errors->get('password')" />
    </div>

    {{-- パスワード確認 --}}
    <div>
        <x-inputs.input-label value="パスワード（確認）" :required="$isCreate" />
        <x-inputs.text-input name="password_confirmation" type="password" value="" autocomplete="new-password" class="w-full" />
    </div>

    {{-- ロール --}}
    <div>
        <x-inputs.input-label value="ロール" :required="true" />
        <x-inputs.select name="role" class="w-full">
            @php $selectedRole = old('role', $user?->roles->first()?->name) @endphp
            <option value="">選択してください</option>
            @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ $selectedRole === $role->name ? 'selected' : '' }}>
                    {{ match($role->name) {
                        'admin'       => '管理者',
                        'sales'       => '営業',
                        'manufacture' => '製造',
                        'warehouse'   => '倉庫',
                        default       => $role->name,
                    } }}
                </option>
            @endforeach
        </x-inputs.select>
        <x-inputs.input-error :messages="$errors->get('role')" />
    </div>

</div>
