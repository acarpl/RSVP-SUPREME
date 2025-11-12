@extends('layouts.app')

@section('content')
<div class="font-poppins bg-gray-50 text-gray-800">

    <!-- 🔴 Navbar -->
    <nav class="bg-red-600 fixed top-0 left-0 w-full z-50 shadow-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between py-4 px-6">
            <a href="/" class="text-white text-2xl font-bold tracking-wide hover:opacity-90 transition">
                SPORTYKUY
            </a>

            <div class="flex items-center space-x-6">
                <a href="/" class="text-white hover:text-gray-200 font-medium transition">Beranda</a>
                <a href="#lapangan" class="text-white hover:text-gray-200 font-medium transition">Lapangan</a>
                <a href="#produk" class="text-white hover:text-gray-200 font-medium transition">Produk</a>

                @guest
                    <!-- Belum login -->
                    <a href="{{ route('register') }}"
                       class="border border-white text-white px-4 py-2 rounded-full font-medium hover:bg-white hover:text-red-600 transition">
                        Daftar
                    </a>
                    <a href="{{ route('login') }}"
                       class="border border-white text-white px-4 py-2 rounded-full font-medium hover:bg-white hover:text-red-600 transition">
                        Login
                    </a>
                @endguest

                @auth
                    <!-- Sudah login -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-red-600 font-bold uppercase">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown profil -->
                        <div x-show="open" @click.away="open = false"
                             class="absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-30 origin-top-right">
                            <div class="p-3 text-gray-700 border-b text-sm">
                                <p class="font-semibold">{{ Auth::user()->name }}</p>
                                <p class="text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" 
                               class="block px-4 py-2 text-gray-600 text-sm hover:bg-red-100 hover:text-red-600 transition">
                                Profil Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-gray-600 text-sm hover:bg-red-100 hover:text-red-600 transition">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- 🟢 Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center bg-center bg-cover pt-20"
        style="background-image: url('https://images.unsplash.com/photo-1594470164118-e1b4c78b6b90?auto=format&fit=crop&w=1920&q=80');">

        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <div class="relative z-10 text-center text-white px-6 animate-fadeIn">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg">Booking Lapangan Mudah!</h1>
            <p class="text-lg md:text-xl mb-6">
                Cari, pesan, dan main bareng teman di 
                <span class="font-semibold text-green-400">Sportykuy ⚽🏸🏀</span>
            </p>

            @auth
                <a href="{{ route('booking.index') }}"
                    class="bg-white text-green-600 font-semibold px-8 py-3 rounded-full shadow-md hover:bg-gray-100 transition">
                    Pesan Sekarang
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="bg-white text-green-600 font-semibold px-8 py-3 rounded-full shadow-md hover:bg-gray-100 transition">
                    Pesan Sekarang (Login Dulu)
                </a>
            @endauth
        </div>
    </section>

    <!-- 🔴 Section #KaburAjaDulu -->
    <section id="kaburajadulu" class="bg-red-600 text-white py-20 text-center">
        <div class="max-w-5xl mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">#KaburAjaDulu</h2>
            <p class="text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                Capek kerja? Stres kuliah? Jangan diem di rumah! <br>
                Booking lapangan sekarang dan main bareng teman-temanmu 🎾⚽🏸
            </p>
        </div>
    </section>

</div>

<!-- 🎬 Animasi -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 1s ease-out;
    }
</style>
@endsection
