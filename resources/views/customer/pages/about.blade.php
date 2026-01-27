@extends('customer.layouts.app')

@section('title', 'About Us')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Bebas+Neue&display=swap" rel="stylesheet">
<style>
    [x-cloak] { display: none !important; }

    /* Marquee Animation */
    @keyframes marquee-left {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    @keyframes marquee-right {
        0% { transform: translateX(-50%); }
        100% { transform: translateX(0); }
    }

    .animate-marquee-left {
        animation: marquee-left 40s linear infinite;
    }
    .animate-marquee-right {
        animation: marquee-right 40s linear infinite;
    }

    /* Pause saat hover */
    .hover-pause:hover .animate-marquee-left,
    .hover-pause:hover .animate-marquee-right {
        animation-play-state: paused;
    }

    /* Floating Animation */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    .animate-bounce-slow { animation: float 6s ease-in-out infinite; }

    /* Scroll Reveal */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen" x-data="{
    stats: { customers: 0, products: 0, brands: 0, rating: 0 },
    animateStats() {
        const duration = 2000;
        const targets = { customers: 10000, products: 500, brands: 50, rating: 4.8 };
        const startTime = Date.now();
        const animate = () => {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            this.stats.customers = Math.floor(targets.customers * eased);
            this.stats.products = Math.floor(targets.products * eased);
            this.stats.brands = Math.floor(targets.brands * eased);
            this.stats.rating = (targets.rating * eased).toFixed(1);
            if (progress < 1) requestAnimationFrame(animate);
        };
        animate();
    }
}" x-init="setTimeout(() => animateStats(), 500)">

<section class="w-full px-4 md:px-8 font-sans">
    <div class="bg-[#1A1A1D] rounded-[2.5rem] overflow-hidden min-h-[550px] md:min-h-[400px] flex items-stretch my-8 md:my-12 relative w-full">

        <div class="container mx-auto px-6 lg:px-12 flex flex-col justify-center h-full relative z-10">
            <div class="flex flex-col items-center justify-center h-full">
                <div class="text-white space-y-6 text-center">
                    <h1 class="text-5xl lg:text-7xl font-bold leading-tight">
                        About Us
                    </h1>
                    <p class="text-lg lg:text-xl font-medium leading-relaxed opacity-90 max-w-2xl mx-auto text-gray-300">
                        THE PARANOIA terbentuk dari latar yang berbeda dan kondisi yang tidak selalu ramah. Awalnya bukan brand, melainkan ruang untuk mengekspresikan apa yang dialami dan dirasakan.
                    </p>
                    <p class="text-lg lg:text-xl font-medium leading-relaxed opacity-90 max-w-2xl mx-auto text-gray-300">
                        Hari ini, nilai yang sama kami bawa ke bentuk yang lebih nyata. Setiap koleksi dirancang sebagai medium cerita—tentang hidup, kebersamaan, dan proses bergerak ke depan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

    <section class="bg-white py-20 lg:py-28 overflow-hidden font-sans">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                <div>
                    <h2 class="text-5xl lg:text-7xl font-bold text-black mb-6 leading-tight">
                        Koleksi
                    </h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-12 max-w-lg">
                        Dibentuk dari pengalaman
                    </p>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-white p-8 rounded-[30px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] text-center border border-gray-100 hover:-translate-y-2 transition duration-300">
                            <div class="flex justify-center items-start gap-1">
                                <span class="text-lg font-bold text-black">Terbentuk sebelum disebut brand</span>
                            </div>
                        </div>
                        <div class="bg-white p-8 rounded-[30px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] text-center border border-gray-100 hover:-translate-y-2 transition duration-300">
                            <div class="flex justify-center items-start gap-1">
                                <span class="text-lg font-bold text-black">Dirilis dalam jumlah terbatas</span>
                            </div>
                        </div>
                        <div class="bg-white p-8 rounded-[30px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] text-center border border-gray-100 hover:-translate-y-2 transition duration-300">
                            <div class="flex justify-center items-start gap-1">
                                <span class="text-lg font-bold text-black">Terbatas karena pilihan</span>
                            </div>
                        </div>
                        <div class="bg-white p-8 rounded-[30px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] text-center border border-gray-100 hover:-translate-y-2 transition duration-300">
                            <div class="flex justify-center items-start gap-1">
                                <span class="text-lg font-bold text-black">Dibuat dengan pengalaman nyata</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative mt-12 lg:mt-0 h-[500px] lg:h-[600px] w-full">
                    <div class="absolute top-0 right-0 w-3/4 h-64 lg:h-80 rounded-3xl overflow-hidden z-0">
                        <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                             alt="Factory Background"
                             class="w-full h-full object-cover opacity-90 grayscale hover:grayscale-0 transition duration-700">
                    </div>
                    <div class="absolute top-20 lg:top-32 left-0 lg:left-8 w-2/3 h-96 lg:h-[450px] rounded-3xl overflow-hidden z-10 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1512413914633-b5043f4041ea?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
                             alt="Sewing Machine Detail"
                             class="w-full h-full object-cover grayscale hover:grayscale-0 transition duration-500">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-12 lg:py-16 font-sans overflow-hidden px-4 md:px-8">
        <div class="w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full">
                        <!-- Card 1: Our Official Sites -->
                        <div class="min-h-[320px] flex flex-col justify-center items-center bg-black p-10 rounded-[30px] shadow-xl text-center text-white transform hover:-translate-y-2 transition duration-300">
                            <div class="mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-4">Our Official Sites</h3>
                            <p class="text-white/80 leading-relaxed text-sm">
                                Discover all our official platforms and stay connected with The Paranoia.
                            </p>
                        </div>

                        <!-- Card 2: Instagram -->
                        <a href="https://instagram.com/theparanoia.official" target="_blank" class="min-h-[320px] flex flex-col justify-center items-center bg-white p-10 rounded-[30px] shadow-[0_20px_50px_rgba(0,0,0,0.05)] text-center border border-gray-100 transform hover:-translate-y-2 transition duration-300 group">
                            <div class="mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-gray-800 group-hover:text-black transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Instagram</h3>
                            <p class="text-gray-500 leading-relaxed text-sm">
                                Follow us on Instagram for the latest updates and exclusive content.
                            </p>
                        </a>

                        <!-- Card 3: Shopee -->
                        <a href="https://shopee.co.id/shop/1639591837" target="_blank" class="min-h-[320px] flex flex-col justify-center items-center bg-white p-10 rounded-[30px] shadow-[0_20px_50px_rgba(0,0,0,0.05)] text-center border border-gray-100 transform hover:-translate-y-2 transition duration-300 group">
                            <div class="mb-6">
                                <img src="/ui/marketplace/shopee.png" alt="Shopee" class="h-20 w-20 mx-auto object-contain grayscale brightness-50 group-hover:brightness-0 transition" />
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Shopee</h3>
                            <p class="text-gray-500 leading-relaxed text-sm">
                                Shop our official products on Shopee for secure and easy transactions.
                            </p>
                        </a>

                        <!-- Card 4: WhatsApp -->
                        <a href="https://wa.me/6282310885367" target="_blank" class="min-h-[320px] flex flex-col justify-center items-center bg-white p-10 rounded-[30px] shadow-[0_20px_50px_rgba(0,0,0,0.05)] text-center border border-gray-100 transform hover:-translate-y-2 transition duration-300 group">
                            <div class="mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-gray-800 group-hover:text-black transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">WhatsApp</h3>
                            <p class="text-gray-500 leading-relaxed text-sm">
                                Chat with us directly on WhatsApp for fast customer support and inquiries.
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-20 lg:py-28 font-sans">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center max-w-3xl mx-auto mb-16 lg:mb-24">
                <h2 class="text-4xl lg:text-6xl font-bold text-black leading-tight mb-6">
                    How to <span class="text-gray-400">Order</span><br>
                    The Paranoia products
                </h2>
            </div>

            <div class="flex flex-col lg:flex-row items-start justify-between gap-12 lg:gap-8 relative">
                <div class="flex flex-col items-center text-center flex-1 w-full">
                    <div class="w-24 h-24 rounded-full bg-black flex items-center justify-center text-white text-4xl font-bold mb-8 shadow-[0_10px_30px_rgba(0,0,0,0.2)] transition hover:scale-110 duration-300">
                        1
                    </div>
                    <h3 class="text-2xl font-bold text-black mb-4">Select your product</h3>
                    <p class="text-gray-500 leading-relaxed px-4">
                        Pilih koleksi yang tersedia.
                    </p>
                </div>

                <div class="hidden lg:block text-black mt-9 opacity-30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>

                <div class="flex flex-col items-center text-center flex-1 w-full">
                    <div class="w-24 h-24 rounded-full bg-black flex items-center justify-center text-white text-4xl font-bold mb-8 shadow-[0_10px_30px_rgba(0,0,0,0.2)] transition hover:scale-110 duration-300">
                        2
                    </div>
                    <h3 class="text-2xl font-bold text-black mb-4">Payment</h3>
                    <p class="text-gray-500 leading-relaxed px-4">
                        Selesaikan Pembayaran.
                    </p>
                </div>

                <div class="hidden lg:block text-black mt-9 opacity-30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>

                <div class="flex flex-col items-center text-center flex-1 w-full">
                    <div class="w-24 h-24 rounded-full bg-black flex items-center justify-center text-white text-4xl font-bold mb-8 shadow-[0_10px_30px_rgba(0,0,0,0.2)] transition hover:scale-110 duration-300">
                        3
                    </div>
                    <h3 class="text-2xl font-bold text-black mb-4">Delivery</h3>
                    <p class="text-gray-500 leading-relaxed px-4">
                        Pesanan dikirim.
                    </p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
// Scroll reveal animation
document.addEventListener('DOMContentLoaded', function() {
    const reveals = document.querySelectorAll('.reveal');
    const revealOnScroll = () => {
        reveals.forEach(element => {
            const windowHeight = window.innerHeight;
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;
            if (elementTop < windowHeight - elementVisible) {
                element.classList.add('active');
            }
        });
    };
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();
});
</script>
@endpush
