@extends('customer.layouts.app')

@section('title', 'About Us')

@push('styles')
<style>
    [x-cloak] { display: none !important; }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&q=80') center/cover;
        opacity: 0.15;
    }

    .hero-pattern {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: radial-gradient(rgba(250, 212, 112, 0.1) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    /* Floating Elements */
    .float-element {
        animation: float 6s ease-in-out infinite;
    }

    .float-element-delayed {
        animation: float 6s ease-in-out infinite;
        animation-delay: -3s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    /* Gradient Text */
    .gradient-text {
        background: linear-gradient(135deg, #FAD470 0%, #f59e0b 50%, #d97706 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Stats Card */
    .stat-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.4s ease;
    }

    .stat-card:hover {
        background: rgba(250, 212, 112, 0.1);
        border-color: rgba(250, 212, 112, 0.3);
        transform: translateY(-5px);
    }

    /* Story Section */
    .story-image {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
    }

    .story-image::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(45deg, rgba(250, 212, 112, 0.2) 0%, transparent 60%);
    }

    .story-image img {
        transition: transform 0.6s ease;
    }

    .story-image:hover img {
        transform: scale(1.05);
    }

    /* Timeline */
    .timeline-line {
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #FAD470, #f59e0b);
        transform: translateX(-50%);
    }

    .timeline-dot {
        width: 16px;
        height: 16px;
        background: #FAD470;
        border: 4px solid #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 4px rgba(250, 212, 112, 0.3);
        position: relative;
        z-index: 10;
    }

    /* Value Card */
    .value-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 24px;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .value-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #FAD470, #f59e0b);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }

    .value-card:hover::before {
        transform: scaleX(1);
    }

    .value-card:hover {
        border-color: #FAD470;
        box-shadow: 0 20px 60px rgba(250, 212, 112, 0.15);
        transform: translateY(-8px);
    }

    .value-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        transition: all 0.4s ease;
    }

    .value-card:hover .value-icon {
        background: linear-gradient(135deg, #FAD470 0%, #f59e0b 100%);
        transform: rotate(-5deg) scale(1.1);
    }

    .value-card:hover .value-icon i {
        color: white;
    }

    /* Team Card */
    .team-card {
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.4s ease;
        border: 1px solid #e5e7eb;
    }

    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
        border-color: #FAD470;
    }

    .team-image {
        position: relative;
        overflow: hidden;
        height: 280px;
    }

    .team-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .team-card:hover .team-image img {
        transform: scale(1.1);
    }

    .team-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(17, 24, 39, 0.8) 0%, transparent 50%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .team-card:hover .team-overlay {
        opacity: 1;
    }

    .team-social {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        opacity: 0;
        transition: all 0.4s ease;
        display: flex;
        gap: 12px;
    }

    .team-card:hover .team-social {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }

    .social-btn {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: all 0.3s ease;
    }

    .social-btn:hover {
        background: #FAD470;
        color: #1f2937;
        transform: translateY(-3px);
    }

    /* Testimonial Card */
    .testimonial-card {
        background: #fff;
        border-radius: 24px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
        transition: all 0.4s ease;
        position: relative;
    }

    .testimonial-card::before {
        content: '"';
        position: absolute;
        top: 20px;
        right: 30px;
        font-size: 120px;
        font-family: Georgia, serif;
        color: #fef3c7;
        line-height: 1;
    }

    .testimonial-card:hover {
        border-color: #FAD470;
        box-shadow: 0 20px 60px rgba(250, 212, 112, 0.15);
    }

    /* Partner Logo */
    .partner-logo {
        filter: grayscale(100%);
        opacity: 0.5;
        transition: all 0.4s ease;
    }

    .partner-logo:hover {
        filter: grayscale(0%);
        opacity: 1;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(250, 212, 112, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .cta-section::after {
        content: '';
        position: absolute;
        bottom: -50%;
        left: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    /* Scroll Animation */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* Image Gallery Grid */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: repeat(2, 200px);
        gap: 16px;
    }

    .gallery-item:first-child {
        grid-row: span 2;
    }

    .gallery-item {
        border-radius: 16px;
        overflow: hidden;
        position: relative;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .timeline-line { display: none; }
        .gallery-grid {
            grid-template-columns: 1fr;
            grid-template-rows: auto;
        }
        .gallery-item:first-child {
            grid-row: auto;
        }
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

    <!-- Hero Section -->
    <section class="hero-section min-h-[90vh] flex items-center relative">
        <div class="hero-pattern"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center px-4 py-2 bg-amber-500/10 rounded-full mb-6 border border-amber-500/20">
                        <span class="w-2 h-2 bg-amber-500 rounded-full mr-2 animate-pulse"></span>
                        <span class="text-amber-400 text-sm font-medium">Established 2024</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                        We Are
                        <span class="gradient-text block">The Paranoia</span>
                    </h1>
                    
                    <p class="text-lg text-gray-400 mb-8 leading-relaxed max-w-xl">
                        More than just fashion — we're a movement. Empowering individuals to express their unique identity through carefully curated, high-quality apparel that tells a story.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('products') }}" 
                           class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-amber-500 to-yellow-500 text-gray-900 font-bold rounded-xl hover:from-amber-400 hover:to-yellow-400 transition-all duration-300 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:scale-105">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            Explore Collection
                        </a>
                        <a href="#our-story" 
                           class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-600 text-gray-300 font-semibold rounded-xl hover:border-amber-500 hover:text-amber-500 transition-all duration-300">
                            <i class="fas fa-play-circle mr-2"></i>
                            Our Story
                        </a>
                    </div>
                </div>

                <!-- Right - Image Collage -->
                <div class="relative hidden lg:block">
                    <div class="gallery-grid">
                        <div class="gallery-item float-element">
                            <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=600&q=80" alt="Fashion Store">
                        </div>
                        <div class="gallery-item float-element-delayed">
                            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=80" alt="Fashion">
                        </div>
                        <div class="gallery-item float-element">
                            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=400&q=80" alt="Shopping">
                        </div>
                        <div class="gallery-item float-element-delayed">
                            <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?w=400&q=80" alt="Style">
                        </div>
                        <div class="gallery-item float-element">
                            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=400&q=80" alt="Fashion Items">
                        </div>
                    </div>
                    
                    <!-- Floating Badge -->
                    <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl p-4 shadow-xl float-element">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-yellow-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-award text-white text-xl"></i>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Trusted by</div>
                                <div class="font-bold text-gray-900">10,000+ Customers</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-20">
                <div class="stat-card rounded-2xl p-6 text-center">
                    <div class="text-3xl md:text-4xl font-bold text-amber-400 mb-1" x-text="stats.customers.toLocaleString() + '+'">0+</div>
                    <div class="text-gray-400 text-sm">Happy Customers</div>
                </div>
                <div class="stat-card rounded-2xl p-6 text-center">
                    <div class="text-3xl md:text-4xl font-bold text-amber-400 mb-1" x-text="stats.products + '+'">0+</div>
                    <div class="text-gray-400 text-sm">Products</div>
                </div>
                <div class="stat-card rounded-2xl p-6 text-center">
                    <div class="text-3xl md:text-4xl font-bold text-amber-400 mb-1" x-text="stats.brands + '+'">0+</div>
                    <div class="text-gray-400 text-sm">Partner Brands</div>
                </div>
                <div class="stat-card rounded-2xl p-6 text-center">
                    <div class="text-3xl md:text-4xl font-bold text-amber-400 mb-1" x-text="stats.rating + '★'">0★</div>
                    <div class="text-gray-400 text-sm">Average Rating</div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
            <a href="#our-story" class="flex flex-col items-center text-gray-500 hover:text-amber-500 transition-colors">
                <span class="text-sm mb-2">Scroll Down</span>
                <i class="fas fa-chevron-down animate-bounce"></i>
            </a>
        </div>
    </section>

    <!-- Our Story Section -->
    <section id="our-story" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Image -->
                <div class="story-image order-2 lg:order-1">
                    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&q=80" 
                         alt="Our Store" 
                         class="w-full h-[500px] object-cover">
                    
                    <!-- Floating Card -->
                    <div class="absolute -bottom-3 -right-3 bg-white rounded-2xl p-6 shadow-xl max-w-xs hidden md:block">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-yellow-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-rocket text-white text-xl"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">Since 2024</div>
                                <div class="text-sm text-gray-500">Growing Strong</div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Started from passion, now serving thousands of fashion enthusiasts.</p>
                    </div>
                </div>

                <!-- Content -->
                <div class="order-1 lg:order-2">
                    <div class="inline-flex items-center px-4 py-2 bg-amber-100 rounded-full mb-6">
                        <i class="fas fa-book-open text-amber-600 mr-2"></i>
                        <span class="text-amber-700 text-sm font-medium">Our Journey</span>
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                        From a Dream to a<br>
                        <span class="text-amber-600">Fashion Movement</span>
                    </h2>
                    
                    <p class="text-gray-600 text-lg mb-6 leading-relaxed">
                        The Paranoia was born from a simple observation: quality fashion shouldn't be exclusive. Our founder, a passionate fashion enthusiast, set out to create a platform where style meets accessibility.
                    </p>
                    
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        What started in 2024 as a small online boutique has evolved into a thriving community of fashion-forward individuals who believe that great style is a form of self-expression. Every piece in our collection is carefully curated to help you tell your unique story.
                    </p>

                    <!-- Features -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-gem text-amber-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Premium Quality</h4>
                                <p class="text-sm text-gray-500">Only the finest materials</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-hand-holding-heart text-amber-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Customer First</h4>
                                <p class="text-sm text-gray-500">Your satisfaction matters</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-leaf text-amber-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Sustainable</h4>
                                <p class="text-sm text-gray-500">Eco-friendly practices</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-truck text-amber-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Fast Delivery</h4>
                                <p class="text-sm text-gray-500">Nationwide shipping</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-amber-100 rounded-full mb-6">
                    <i class="fas fa-history text-amber-600 mr-2"></i>
                    <span class="text-amber-700 text-sm font-medium">Our Milestones</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    The Journey So Far
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    From humble beginnings to becoming a trusted fashion destination, here's how we've grown.
                </p>
            </div>

            <div class="relative">
                <div class="timeline-line hidden md:block"></div>
                
                <div class="space-y-12">
                    <!-- Timeline Item 1 -->
                    <div class="relative grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="md:text-right md:pr-16">
                            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 inline-block">
                                <span class="text-amber-600 font-bold text-lg">January 2024</span>
                                <h3 class="text-xl font-bold text-gray-900 mt-2 mb-3">The Beginning</h3>
                                <p class="text-gray-600">Launched The Paranoia with just 50 products and a vision to revolutionize online fashion shopping in Indonesia.</p>
                            </div>
                        </div>
                        <div class="hidden md:flex justify-start pl-16">
                            <div class="timeline-dot"></div>
                        </div>
                        <div class="md:hidden absolute left-0 top-0">
                            <div class="timeline-dot"></div>
                        </div>
                    </div>

                    <!-- Timeline Item 2 -->
                    <div class="relative grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="hidden md:flex justify-end pr-16">
                            <div class="timeline-dot"></div>
                        </div>
                        <div class="md:pl-16">
                            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 inline-block">
                                <span class="text-amber-600 font-bold text-lg">June 2024</span>
                                <h3 class="text-xl font-bold text-gray-900 mt-2 mb-3">1,000 Customers</h3>
                                <p class="text-gray-600">Reached our first major milestone of 1,000 happy customers and expanded our product range to 200+ items.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Item 3 -->
                    <div class="relative grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="md:text-right md:pr-16">
                            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 inline-block">
                                <span class="text-amber-600 font-bold text-lg">December 2024</span>
                                <h3 class="text-xl font-bold text-gray-900 mt-2 mb-3">Partnership Growth</h3>
                                <p class="text-gray-600">Partnered with 30+ local and international brands to bring diverse fashion choices to our customers.</p>
                            </div>
                        </div>
                        <div class="hidden md:flex justify-start pl-16">
                            <div class="timeline-dot"></div>
                        </div>
                    </div>

                    <!-- Timeline Item 4 -->
                    <div class="relative grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="hidden md:flex justify-end pr-16">
                            <div class="timeline-dot"></div>
                        </div>
                        <div class="md:pl-16">
                            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 inline-block">
                                <span class="text-amber-600 font-bold text-lg">2025 & Beyond</span>
                                <h3 class="text-xl font-bold text-gray-900 mt-2 mb-3">The Future</h3>
                                <p class="text-gray-600">Continuing to grow with 10,000+ customers, 500+ products, and a commitment to sustainable fashion.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Mission Card -->
                <div class="relative rounded-3xl overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&q=80" 
                         alt="Our Mission" 
                         class="w-full h-[400px] object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-bullseye text-white text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-white">Our Mission</h3>
                        </div>
                        <p class="text-gray-200 leading-relaxed">
                            To empower individuals to express their authentic selves through carefully curated, high-quality fashion that's accessible to everyone. We believe style should never be a barrier to self-expression.
                        </p>
                    </div>
                </div>

                <!-- Vision Card -->
                <div class="relative rounded-3xl overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1555529771-7888783a18d3?w=800&q=80" 
                         alt="Our Vision" 
                         class="w-full h-[400px] object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-eye text-white text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-white">Our Vision</h3>
                        </div>
                        <p class="text-gray-200 leading-relaxed">
                            To become Indonesia's most trusted and innovative fashion platform, setting new standards for quality, sustainability, and customer experience in the digital fashion industry.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-amber-100 rounded-full mb-6">
                    <i class="fas fa-star text-amber-600 mr-2"></i>
                    <span class="text-amber-700 text-sm font-medium">What We Believe</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Our Core Values
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    These principles guide every decision we make and every interaction we have.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Value 1 -->
                <div class="value-card p-8 text-center">
                    <div class="value-icon">
                        <i class="fas fa-heart text-amber-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Passion</h3>
                    <p class="text-gray-600">
                        Fashion is not just our business—it's our passion. We pour love into every product we curate and every customer we serve.
                    </p>
                </div>

                <!-- Value 2 -->
                <div class="value-card p-8 text-center">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt text-amber-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Integrity</h3>
                    <p class="text-gray-600">
                        Transparency and honesty are non-negotiable. We stand behind every product and every promise we make to our customers.
                    </p>
                </div>

                <!-- Value 3 -->
                <div class="value-card p-8 text-center">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb text-amber-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Innovation</h3>
                    <p class="text-gray-600">
                        We constantly evolve, embracing new trends and technologies to enhance your shopping experience and style journey.
                    </p>
                </div>

                <!-- Value 4 -->
                <div class="value-card p-8 text-center">
                    <div class="value-icon">
                        <i class="fas fa-users text-amber-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Community</h3>
                    <p class="text-gray-600">
                        We're building more than a store—we're creating a community of fashion enthusiasts who inspire and support each other.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-amber-100 rounded-full mb-6">
                    <i class="fas fa-users text-amber-600 mr-2"></i>
                    <span class="text-amber-700 text-sm font-medium">The Team</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Meet The People Behind The Paranoia
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Our dedicated team works tirelessly to bring you the best fashion experience possible.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Team Member 1 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80" alt="Team Member">
                        <div class="team-overlay"></div>
                        <div class="team-social">
                            <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h4 class="text-xl font-bold text-gray-900 mb-1">Andi Pratama</h4>
                        <p class="text-amber-600 font-medium mb-3">Founder & CEO</p>
                        <p class="text-gray-500 text-sm">Visionary leader with 10+ years experience in fashion retail industry.</p>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&q=80" alt="Team Member">
                        <div class="team-overlay"></div>
                        <div class="team-social">
                            <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-btn"><i class="fab fa-dribbble"></i></a>
                            <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h4 class="text-xl font-bold text-gray-900 mb-1">Sarah Wijaya</h4>
                        <p class="text-amber-600 font-medium mb-3">Creative Director</p>
                        <p class="text-gray-500 text-sm">Award-winning designer with a passion for sustainable fashion.</p>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&q=80" alt="Team Member">
                        <div class="team-overlay"></div>
                        <div class="team-social">
                            <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-btn"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h4 class="text-xl font-bold text-gray-900 mb-1">Budi Santoso</h4>
                        <p class="text-amber-600 font-medium mb-3">Head of Operations</p>
                        <p class="text-gray-500 text-sm">Logistics expert ensuring every order arrives perfectly and on time.</p>
                    </div>
                </div>

                <!-- Team Member 4 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&q=80" alt="Team Member">
                        <div class="team-overlay"></div>
                        <div class="team-social">
                            <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h4 class="text-xl font-bold text-gray-900 mb-1">Maya Putri</h4>
                        <p class="text-amber-600 font-medium mb-3">Marketing Lead</p>
                        <p class="text-gray-500 text-sm">Digital marketing strategist and brand storyteller extraordinaire.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-amber-100 rounded-full mb-6">
                    <i class="fas fa-comments text-amber-600 mr-2"></i>
                    <span class="text-amber-700 text-sm font-medium">Testimonials</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    What Our Customers Say
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Don't just take our word for it—here's what our amazing customers have to say.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="testimonial-card">
                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&q=80" 
                             alt="Customer" 
                             class="w-14 h-14 rounded-full object-cover">
                        <div>
                            <h4 class="font-semibold text-gray-900">Dian Kusuma</h4>
                            <p class="text-amber-600 text-sm">Jakarta</p>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed relative z-10">
                        "Kualitas produknya luar biasa! Packaging-nya juga rapih dan pengiriman cepat. Sudah jadi langganan tetap di The Paranoia."
                    </p>
                    <div class="flex gap-1 mt-4">
                        <i class="fas fa-star text-amber-400"></i>
                        <i class="fas fa-star text-amber-400"></i>
                        <i class="fas fa-star text-amber-400"></i>
                        <i class="fas fa-star text-amber-400"></i>
                        <i class="fas fa-star text-amber-400"></i>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card">
                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=100&q=80" 
                             alt="Customer" 
                             class="w-14 h-14 rounded-full object-cover">
                        <div>
                            <h4 class="font-semibold text-gray-900">Rizki Hidayat</h4>
                            <p class="text-amber-600 text-sm">Bandung</p>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed relative z-10">
                        "Desainnya keren-keren dan beda dari yang lain. Customer service-nya juga ramah banget. Highly recommended!"
                    </p>
                    <div class="flex gap-1 mt-4">
                        <i class="fas fa-star text-amber-400"></i>
                        <i class="fas fa-star text-amber-400"></i>
                        <i class="fas fa-star text-amber-400"></i>
                        <i class="fas fa-star text-amber-400"></i>
                        <i class="fas fa-star text-amber-400"></i>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card">
                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100&q=80" 
                             alt="Customer" 
                             class="w-14 h-14 rounded-full object-cover">
                        <div>
                            <h4 class="font-semibold text-gray-900">Anisa Rahma</h4>
                            <p class="text-amber-600 text-sm">Surabaya</p>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed relative z-10">
                        "Suka banget sama koleksinya! Harga terjangkau tapi kualitas premium. Pasti bakal order lagi!"
                    </p>
                    <div class="flex gap-1 mt-4">
                        <i class="fas fa-star text-amber-400"></i>
                        <i class="fas fa-star text-amber-400"></i>
                        <i class="fas fa-star text-amber-400"></i>
                        <i class="fas fa-star text-amber-400"></i>
                        <i class="fas fa-star text-amber-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h3 class="text-lg text-gray-500 font-medium">Trusted by Leading Brands</h3>
            </div>
            <div class="flex flex-wrap justify-center items-center gap-12 md:gap-16">
                <div class="partner-logo">
                    <img src="https://via.placeholder.com/150x50/f3f4f6/9ca3af?text=Brand+1" alt="Partner Brand" class="h-10">
                </div>
                <div class="partner-logo">
                    <img src="https://via.placeholder.com/150x50/f3f4f6/9ca3af?text=Brand+2" alt="Partner Brand" class="h-10">
                </div>
                <div class="partner-logo">
                    <img src="https://via.placeholder.com/150x50/f3f4f6/9ca3af?text=Brand+3" alt="Partner Brand" class="h-10">
                </div>
                <div class="partner-logo">
                    <img src="https://via.placeholder.com/150x50/f3f4f6/9ca3af?text=Brand+4" alt="Partner Brand" class="h-10">
                </div>
                <div class="partner-logo">
                    <img src="https://via.placeholder.com/150x50/f3f4f6/9ca3af?text=Brand+5" alt="Partner Brand" class="h-10">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-24 relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center px-4 py-2 bg-amber-500/20 rounded-full mb-6 border border-amber-500/30">
                <i class="fas fa-sparkles text-amber-400 mr-2"></i>
                <span class="text-amber-300 text-sm font-medium">Join Our Community</span>
            </div>
            
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight">
                Ready to Elevate Your<br>
                <span class="text-amber-400">Style Game?</span>
            </h2>
            
            <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
                Join over 10,000 fashion enthusiasts who have discovered their unique style with The Paranoia. Your perfect outfit awaits.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-amber-500 to-yellow-500 text-gray-900 font-bold rounded-xl hover:from-amber-400 hover:to-yellow-400 transition-all duration-300 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:scale-105">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Start Shopping
                </a>
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-600 text-gray-300 font-semibold rounded-xl hover:border-amber-500 hover:text-amber-500 transition-all duration-300">
                    <i class="fas fa-user-plus mr-2"></i>
                    Create Account
                </a>
            </div>

            <!-- Contact Info -->
            <div class="mt-16 pt-12 border-t border-gray-700/50">
                <p class="text-gray-500 mb-6">Have questions? We're here to help!</p>
                <div class="flex flex-wrap justify-center gap-8">
                    <a href="mailto:hello@theparanoia.com" class="flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors">
                        <i class="fas fa-envelope"></i>
                        hello@theparanoia.com
                    </a>
                    <a href="tel:+62211234567" class="flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors">
                        <i class="fas fa-phone"></i>
                        +62 21 1234 567
                    </a>
                    <a href="#" class="flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
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
