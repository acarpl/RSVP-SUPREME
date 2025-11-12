<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-red-600">Buat Akun Sportykuy</h1>
        <p class="text-gray-500 mt-2">Daftar sekarang dan nikmati kemudahan booking lapangan favoritmu!</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Nama -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-700 font-semibold" />
            <x-text-input id="name"
                          class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-lg shadow-sm"
                          type="text"
                          name="name"
                          :value="old('name')"
                          required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
            <x-text-input id="email"
                          class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-lg shadow-sm"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
            <x-text-input id="password"
                          class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-lg shadow-sm"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
        </div>

        <!-- Konfirmasi Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-700 font-semibold" />
            <x-text-input id="password_confirmation"
                          class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-lg shadow-sm"
                          type="password"
                          name="password_confirmation"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600" />
        </div>

        <!-- Tombol Register -->
        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-red-600 text-white font-semibold py-2.5 rounded-lg shadow-md hover:bg-red-700 transition duration-200">
                {{ __('Daftar Sekarang') }}
            </button>
        </div>

        <!-- Sudah punya akun -->
        <div class="text-center text-sm text-gray-600 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-red-600 font-semibold hover:underline">Masuk di sini</a>
        </div>
    </form>
</x-guest-layout>
