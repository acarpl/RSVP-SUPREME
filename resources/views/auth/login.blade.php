<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-red-600">Selamat Datang di Sportykuy</h1>
        <p class="text-gray-500 mt-2">Masuk untuk melanjutkan booking lapanganmu!</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
            <x-text-input id="email"
                          class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-lg shadow-sm"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
            <x-text-input id="password"
                          class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-lg shadow-sm"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-red-600 focus:ring-red-500"
                       name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-red-600 hover:underline"
                   href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-red-600 text-white font-semibold py-2.5 rounded-lg shadow-md hover:bg-red-700 transition duration-200">
                {{ __('Masuk') }}
            </button>
        </div>

        <!-- Register Redirect -->
        <div class="text-center text-sm text-gray-600 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-red-600 font-semibold hover:underline">Daftar Sekarang</a>
        </div>
    </form>
</x-guest-layout>
