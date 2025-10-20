<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
    <!-- Indikator -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>

    <!-- Item Carousel -->
    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
            <img src="{{ asset('images/Futsal.svg') }}" class="d-block w-100" alt="Lapangan Futsal">
            <div class="carousel-caption d-none d-md-block">
                <h2 class="text-white fw-bold">Booking Lapangan Mudah!</h2>
                <p class="fs-5">Cari, pesan, main — semua dalam satu aplikasi.</p>
                <a href="#" class="btn btn-light btn-lg rounded-pill px-4">Pesan Sekarang</a>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
            <img src="{{ asset('images/Voli.svg') }}" class="d-block w-100" alt="Voucher Diskon">
            <div class="carousel-caption d-none d-md-block">
                <h2 class="text-white fw-bold">Diskon Hingga 50%</h2>
                <p class="fs-5">Gunakan voucher eksklusif untuk booking lapangan favoritmu.</p>
                <a href="#" class="btn btn-outline-light btn-lg rounded-pill px-4">Lihat Voucher</a>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <img src="{{ asset('images/Tennis.svg') }}" class="d-block w-100" alt="Merchandise Sporty">
            <div class="carousel-caption d-none d-md-block">
                <h2 class="text-white fw-bold">Merchandise Resmi</h2>
                <p class="fs-5">Jersey, bola, tas — semuanya original!</p>
                <a href="#" class="btn btn-light btn-lg rounded-pill px-4">Belanja Sekarang</a>
            </div>
        </div>
    </div>

    <!-- Kontrol -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<style>
    /* --- FADE TRANSITION --- */
    .carousel-fade .carousel-item {
        opacity: 0;
        transition-property: opacity;
        transition-duration: 1s;
    }

    .carousel-fade .carousel-item.active,
    .carousel-fade .carousel-item-next.carousel-item-start,
    .carousel-fade .carousel-item-prev.carousel-item-end {
        opacity: 1;
    }

    .carousel-fade .carousel-item-next,
    .carousel-fade .carousel-item-prev,
    .carousel-fade .carousel-item.active {
        transform: translateX(0);
        transition: opacity 1s ease-in-out;
    }

    /* --- PARALLAX EFFECT --- */
    .parallax-bg {
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
        height: 60vh;
    }

    /* --- STYLING CAROUSEL --- */
    .carousel-item img {
        height: 60vh;
        object-fit: cover;
    }

    .carousel-caption {
        bottom: 20%;
        text-shadow: 0 2px 4px rgba(0,0,0,0.7);
    }

    .carousel-caption h2 {
        font-size: 2.5rem;
    }

    @media (max-width: 768px) {
        .carousel-item img {
            height: 50vh;
        }
        .carousel-caption h2 {
            font-size: 1.8rem;
        }
        .carousel-caption p {
            font-size: 1rem;
        }
    }
</style>

<script>
    // Aktifkan fade effect secara manual karena Bootstrap 5 tidak punya built-in fade class
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = new bootstrap.Carousel(document.getElementById('heroCarousel'), {
            interval: 4000,
            wrap: true,
            ride: 'carousel'
        });

        // Tambah class fade ke carousel
        document.querySelector('#heroCarousel').classList.add('carousel-fade');
    });
</script>