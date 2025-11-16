<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ==== BAGIAN BADGE ROLE ==== --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-bold mb-3">Status Akun</h3>

                {{-- Badge Role --}}
                @if(auth()->user()->role == 'super_admin')
                    <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-sm">
                        ⭐ Super Admin
                    </span>
                @elseif(auth()->user()->role == 'partner')
                    <span class="px-3 py-1 bg-green-600 text-white rounded-full text-sm">
                        🤝 Mitra Sportykuy
                    </span>
                @else
                    <span class="px-3 py-1 bg-gray-400 text-white rounded-full text-sm">
                        👤 Customer
                    </span>
                @endif

                {{-- Tombol Gabung Mitra --}}
                @if(auth()->user()->role == 'customer')
                    <form action="{{ route('gabung.mitra') }}" method="POST" class="mt-4">
                        @csrf
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg">
                            Gabung Menjadi Mitra
                        </button>
                    </form>
                @endif
            </div>

            {{-- ==== FORM UPDATE PROFILE ==== --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- ==== FORM UPDATE PASSWORD ==== --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- ==== DELETE USER ==== --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
