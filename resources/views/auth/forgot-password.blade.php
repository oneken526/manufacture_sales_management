<x-guest-layout>
    <h2 class="text-xl font-bold text-center text-gray-800 mb-6">パスワードリセット</h2>

    <div class="mb-4 text-sm text-gray-600">
        パスワードをお忘れですか？メールアドレスを入力してください。パスワードリセット用のリンクをお送りします。
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- メールアドレス -->
        <div>
            <x-input-label for="email" :value="__('メールアドレス')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                パスワードリセットリンクを送信
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
