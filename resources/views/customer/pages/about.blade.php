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
                             alt="Model Fashion"
                             class="hidden md:block w-full h-auto object-cover relative z-10 block"
                        >

                        <!-- <div class="absolute top-10 -left-4 lg:-left-20 z-20 bg-white p-3 pr-6 rounded-2xl shadow-xl flex items-center gap-4 animate-bounce-slow">
                            <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center text-white flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <div class="flex flex-col text-sm font-bold text-gray-900 leading-tight">
                                <span>Bonus Mac OS</span>
                                <span>Capitan Pro</span>
                            </div>
                        </div> -->

                        <!-- <div class="absolute bottom-12 -right-2 lg:-right-10 z-20 bg-white p-5 rounded-3xl shadow-xl flex flex-col items-center gap-2 text-center w-32">
                            <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center text-white mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>
                            <div class="text-xs font-bold text-gray-900 leading-tight">
                                Include<br>Warranty
                            </div>
                        </div> -->

                    </div>
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
                        Shopping
                    </h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-12 max-w-lg">
                        Explore our premium selection. From heavy-weight tees to structured outerwear. Crafted for those who find color in the darkness.
                    </p>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-white p-8 rounded-[30px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] text-center border border-gray-100 hover:-translate-y-2 transition duration-300">
                            <div class="flex justify-center items-start gap-1">
                                <span class="text-4xl font-bold text-black">13</span>
                                <span class="text-black text-2xl font-bold mt-1">+</span>
                            </div>
                            <p class="text-gray-500 text-sm mt-2 font-medium">Years Experience</p>
                        </div>
                        <div class="bg-white p-8 rounded-[30px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] text-center border border-gray-100 hover:-translate-y-2 transition duration-300">
                            <div class="flex justify-center items-start gap-1">
                                <span class="text-4xl font-bold text-black">100K</span>
                                <span class="text-black text-2xl font-bold mt-1">+</span>
                            </div>
                            <p class="text-gray-500 text-sm mt-2 font-medium">Fashion And Brand</p>
                        </div>
                        <div class="bg-white p-8 rounded-[30px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] text-center border border-gray-100 hover:-translate-y-2 transition duration-300">
                            <div class="flex justify-center items-start gap-1">
                                <span class="text-4xl font-bold text-black">6K</span>
                                <span class="text-black text-2xl font-bold mt-1">+</span>
                            </div>
                            <p class="text-gray-500 text-sm mt-2 font-medium">Order completed</p>
                        </div>
                        <div class="bg-white p-8 rounded-[30px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] text-center border border-gray-100 hover:-translate-y-2 transition duration-300">
                            <div class="flex justify-center items-start gap-1">
                                <span class="text-4xl font-bold text-black">99</span>
                                <span class="text-black text-2xl font-bold mt-1">+</span>
                            </div>
                            <p class="text-gray-500 text-sm mt-2 font-medium">Partners</p>
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

    <section class="bg-white py-20 lg:py-28 font-sans overflow-hidden">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-start">

                <div class="lg:col-span-4 space-y-8 sticky top-10">
                    <h2 class="text-5xl lg:text-6xl font-bold text-black leading-tight">
                        What can our <br>
                        <span class="text-gray-400">Shopping <br> Offers?</span>
                    </h2>
                    <div class="space-y-6 text-gray-500 text-lg leading-relaxed">
                        <p>
                            Heavy-weight fabrics and precision cuts. Built to last in the concrete jungle.
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="aspect-square flex flex-col justify-center items-center bg-black p-8 rounded-[30px] shadow-xl text-center text-white transform hover:-translate-y-2 transition duration-300">
                            <div class="mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-3">Get many product</h3>
                            <p class="text-white/80 leading-relaxed text-sm px-2">
                                Explore our vast arsenal of monochrome aesthetics. From heavy-weight tees to technical outerwear, we curate the chaos for you.
                            </p>
                        </div>

                        <div class="aspect-square flex flex-col justify-center items-center bg-white p-8 rounded-[30px] shadow-[0_20px_50px_rgba(0,0,0,0.05)] text-center border border-gray-100 transform hover:-translate-y-2 transition duration-300">
                            <div class="mb-6 text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">Earn Rewards</h3>
                            <p class="text-gray-500 leading-relaxed text-sm px-2">
                                Don't just spend, invest. Accumulate points with every acquisition and redeem them for exclusive gear. Join the inner circle.
                            </p>
                        </div>

                        <div class="aspect-square flex flex-col justify-center items-center bg-white p-8 rounded-[30px] shadow-[0_20px_50px_rgba(0,0,0,0.05)] text-center border border-gray-100 transform hover:-translate-y-2 transition duration-300">
                            <div class="mb-6 text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">Rapid Dispatch</h3>
                            <p class="text-gray-500 leading-relaxed text-sm px-2">
                                We move fast. Orders are processed with urgency and shipped worldwide directly to your doorstep. No delays, no excuses.
                            </p>
                        </div>

                        <div class="aspect-square flex flex-col justify-center items-center bg-white p-8 rounded-[30px] shadow-[0_20px_50px_rgba(0,0,0,0.05)] text-center border border-gray-100 transform hover:-translate-y-2 transition duration-300">
                            <div class="mb-6 text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">100% Authentic</h3>
                            <p class="text-gray-500 leading-relaxed text-sm px-2">
                                No fakes, no compromises. Every piece is guaranteed original. Shop securely with encrypted transactions.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-20 lg:py-28 font-sans">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center max-w-3xl mx-auto mb-16 lg:mb-24">
                <h2 class="text-4xl lg:text-6xl font-bold text-black leading-tight mb-6">
                    How the <span class="text-gray-400">Ordering</span><br>
                    The Product??
                </h2>
                <p class="text-gray-500 text-lg leading-relaxed">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>
            </div>

            <div class="flex flex-col lg:flex-row items-start justify-between gap-12 lg:gap-8 relative">
                <div class="flex flex-col items-center text-center flex-1 w-full">
                    <div class="w-24 h-24 rounded-full bg-black flex items-center justify-center text-white text-4xl font-bold mb-8 shadow-[0_10px_30px_rgba(0,0,0,0.2)] transition hover:scale-110 duration-300">
                        1
                    </div>
                    <h3 class="text-2xl font-bold text-black mb-4">Select your product</h3>
                    <p class="text-gray-500 leading-relaxed px-4">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
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
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
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
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-20 lg:py-28 font-sans overflow-hidden hover-pause">
        <div class="container mx-auto px-6 lg:px-12 text-center mb-16">
            <h2 class="text-4xl lg:text-6xl font-bold text-black leading-tight mb-6">
                Trusted by 500+ <span class="text-gray-400">startups</span><br>
                and agencies
            </h2>
            <p class="text-gray-500 text-lg leading-relaxed max-w-3xl mx-auto">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </p>
        </div>

        <div class="flex flex-col gap-10">

            <div class="relative w-full overflow-hidden fade-mask">
                <div class="flex w-max animate-marquee-left gap-8" style="animation-duration: 40s;">
                    <div class="flex gap-8">
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="text-left">
                                <h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">BADIE LLC</h1>
                                <p class="text-sm text-gray-400 font-medium mt-1">Is Golden</p>
                            </div>
                        </div>
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/></svg>
                            </div>
                            <div class="text-left">
                                <h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">WUCKERT INC</h1>
                                <p class="text-sm text-gray-400 font-medium mt-1">Gold Time</p>
                            </div>
                        </div>
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            </div>
                            <div class="text-left">
                                <h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">SCHUMM</h1>
                                <p class="text-sm text-gray-400 font-medium mt-1">Work Nation</p>
                            </div>
                        </div>
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="text-left">
                                <h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">BAYER</h1>
                                <p class="text-sm text-gray-400 font-medium mt-1">Children</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-8">
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg></div>
                            <div class="text-left"><h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">BADIE LLC</h1><p class="text-sm text-gray-400 font-medium mt-1">Is Golden</p></div>
                        </div>
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/></svg></div>
                            <div class="text-left"><h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">WUCKERT INC</h1><p class="text-sm text-gray-400 font-medium mt-1">Gold Time</p></div>
                        </div>
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg></div>
                            <div class="text-left"><h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">SCHUMM</h1><p class="text-sm text-gray-400 font-medium mt-1">Work Nation</p></div>
                        </div>
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg></div>
                            <div class="text-left"><h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">BAYER</h1><p class="text-sm text-gray-400 font-medium mt-1">Children</p></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative w-full overflow-hidden fade-mask">
                <div class="flex w-max animate-marquee-right gap-8" style="animation-duration: 40s;">
                    <div class="flex gap-8">
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="text-left">
                                <h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">LABADIE</h1>
                                <p class="text-sm text-gray-400 font-medium mt-1">Time Golden</p>
                            </div>
                        </div>
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/></svg>
                            </div>
                            <div class="text-left">
                                <h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">GUTMANN</h1>
                                <p class="text-sm text-gray-400 font-medium mt-1">Quality First</p>
                            </div>
                        </div>
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="text-left">
                                <h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">LARKIN</h1>
                                <p class="text-sm text-gray-400 font-medium mt-1">Best Agency</p>
                            </div>
                        </div>
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            </div>
                            <div class="text-left">
                                <h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">ROWE CO</h1>
                                <p class="text-sm text-gray-400 font-medium mt-1">Creative</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-8">
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg></div>
                            <div class="text-left"><h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">LABADIE</h1><p class="text-sm text-gray-400 font-medium mt-1">Time Golden</p></div>
                        </div>
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/></svg></div>
                            <div class="text-left"><h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">GUTMANN</h1><p class="text-sm text-gray-400 font-medium mt-1">Quality First</p></div>
                        </div>
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg></div>
                            <div class="text-left"><h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">LARKIN</h1><p class="text-sm text-gray-400 font-medium mt-1">Best Agency</p></div>
                        </div>
                        <div class="min-w-[380px] bg-white rounded-[20px] shadow-[0_8px_30px_rgba(0,0,0,0.06)] p-6 flex items-center gap-5 hover:shadow-[0_15px_35px_rgba(0,0,0,0.1)] transition-shadow duration-300 border border-gray-100">
                            <div class="w-16 h-16 bg-gray-800 rounded-xl shrink-0 flex items-center justify-center text-white"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg></div>
                            <div class="text-left"><h1 class="font-bold text-gray-800 text-xl uppercase tracking-wide">ROWE CO</h1><p class="text-sm text-gray-400 font-medium mt-1">Creative</p></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="bg-white py-20 lg:py-28 font-sans overflow-hidden">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-4xl lg:text-6xl font-bold text-black leading-tight mb-6">
                    Hear The<br>
                    <span class="text-gray-400">Noise</span>
                </h2>
                <p class="text-gray-500 text-lg leading-relaxed">
                    Real words from the underground. See why the community chooses The Paranoia.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-16 px-4 lg:px-0 pb-10">
                <div class="relative bg-black rounded-[40px] px-8 pb-10 pt-16 text-center shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] hover:-translate-y-2 transition duration-300">
                    <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 w-20 h-20 rounded-full overflow-hidden shadow-lg bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80" alt="Pedro Beatty" class="w-full h-full object-cover">
                    </div>
                    <h4 class="text-xl font-bold text-white mb-4">Pedro Beatty</h4>
                    <p class="text-gray-300 text-sm leading-relaxed mb-8">
                        The heavy-weight fabric is insane. Fits perfectly oversized without looking sloppy. Finally, a brand that understands the architecture of streetwear.
                    </p>
                    <div class="flex justify-center gap-1.5 text-white">
                        @for($i=0; $i<5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                </div>

                <div class="relative bg-white rounded-[40px] px-8 pb-10 pt-16 text-center shadow-[0_35px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100 hover:-translate-y-2 transition duration-300">
                    <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 w-20 h-20 rounded-full overflow-hidden shadow-lg bg-gray-200">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80" alt="Justin Gerhold Jr." class="w-full h-full object-cover">
                    </div>
                    <h4 class="text-xl font-bold text-black mb-4">Justin Gerhold Jr.</h4>
                    <p class="text-gray-500 text-sm leading-relaxed mb-8">
                        Monochrome perfection. No loud logos, just pure silhouette and texture. My daily uniform for the concrete jungle.
                    </p>
                    <div class="flex justify-center gap-1.5 text-black">
                        @for($i=0; $i<5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                </div>

                <div class="relative bg-white rounded-[40px] px-8 pb-10 pt-16 text-center shadow-[0_35px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100 hover:-translate-y-2 transition duration-300">
                    <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 w-20 h-20 rounded-full overflow-hidden shadow-lg bg-gray-200">
                        <img src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80" alt="Rufus Mohr" class="w-full h-full object-cover">
                    </div>
                    <h4 class="text-xl font-bold text-black mb-4">Rufus Mohr</h4>
                    <p class="text-gray-500 text-sm leading-relaxed mb-8">
                        Packaging was an experience in itself. Fast dispatch, zero hassle. The Paranoia sets a new standard for local brands.
                    </p>
                    <div class="flex justify-center gap-1.5 text-black">
                        @for($i=0; $i<5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
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
