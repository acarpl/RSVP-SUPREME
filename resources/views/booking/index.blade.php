@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col">

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-red-600 via-red-500 to-green-500 text-white text-center py-24 shadow-lg">
        <div class="max-w-3xl mx-auto px-6">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-4 tracking-wide drop-shadow-lg">
                Booking Lapangan Mudah!
            </h1>
            <p class="text-lg md:text-xl mb-8 opacity-90 font-light">
                Cari, pesan, dan main bareng teman di <span class="font-semibold">Sportykuy ⚽🏸🏀</span>
            </p>
            <a href="#booking-section"
               class="bg-white text-red-600 px-8 py-3 rounded-full font-semibold shadow-md hover:bg-gray-100 hover:scale-105 transition duration-300">
               Pesan Sekarang
            </a>
        </div>

        <!-- Decorative Wave -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0]">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="w-full h-20">
                <path d="M-0.00,49.98 C150.00,150.00 349.62,-49.98 500.00,49.98 L500.00,150.00 L-0.00,150.00 Z"
                      class="fill-white"></path>
            </svg>
        </div>
    </section>

    <!-- Booking Section -->
    <section id="booking-section" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-extrabold text-gray-800 mb-3">Pilih Lapangan Favoritmu</h2>
            <p class="text-gray-500 mb-12">
                Nikmati kemudahan booking berbagai jenis lapangan olahraga hanya di <span class="text-red-600 font-semibold">Sportykuy</span>.
            </p>

            <!-- Grid Lapangan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">

                <!-- Card 1 -->
                <div class="bg-gray-50 rounded-3xl shadow-lg overflow-hidden transform hover:-translate-y-2 hover:shadow-2xl transition duration-300">
                    <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=800&q=80"
                         alt="Lapangan Futsal" class="h-56 w-full object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Lapangan Futsal</h3>
                        <p class="text-sm text-gray-500 mb-5">Main bareng teman dengan lapangan futsal indoor berstandar tinggi!</p>
                        <a href="#" class="bg-red-600 text-white px-6 py-2 rounded-full text-sm font-semibold hover:bg-red-700 transition">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-gray-50 rounded-3xl shadow-lg overflow-hidden transform hover:-translate-y-2 hover:shadow-2xl transition duration-300">
                    <img src="https://images.unsplash.com/photo-1581167768338-3e89eaa9c665?auto=format&fit=crop&w=800&q=80"
                         alt="Lapangan Badminton" class="h-56 w-full object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Lapangan Badminton</h3>
                        <p class="text-sm text-gray-500 mb-5">Tunjukkan smash terbaikmu di lapangan badminton profesional.</p>
                        <a href="#" class="bg-red-600 text-white px-6 py-2 rounded-full text-sm font-semibold hover:bg-red-700 transition">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-gray-50 rounded-3xl shadow-lg overflow-hidden transform hover:-translate-y-2 hover:shadow-2xl transition duration-300">
                    <img src="https://images.unsplash.com/photo-1533106418989-88406c7cc8e1?auto=format&fit=crop&w=800&q=80"
                         alt="Lapangan Basket" class="h-56 w-full object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Lapangan Basket</h3>
                        <p class="text-sm text-gray-500 mb-5">Ayo tunjukkan kemampuan dribble dan shooting-mu!</p>
                        <a href="#" class="bg-red-600 text-white px-6 py-2 rounded-full text-sm font-semibold hover:bg-red-700 transition">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-green-500 to-green-600 text-white py-16 text-center">
        <h3 class="text-3xl font-bold mb-4">Siap Buat Keringat Hari Ini? 💪</h3>
        <p class="text-lg opacity-90 mb-6">Sportykuy siap bantu kamu cari lapangan terbaik sesuai waktu dan kebutuhanmu!</p>
        <a href="#"
           class="bg-white text-green-700 px-8 py-3 rounded-full font-semibold shadow-md hover:bg-gray-100 hover:scale-105 transition">
           Mulai Booking Sekarang
        </a>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-6 text-center text-gray-500 text-sm border-t">
        © 2025 <span class="text-red-600 font-semibold">Sportykuy</span>. Semua hak dilindungi.
    </footer>
</div>
@endsection
