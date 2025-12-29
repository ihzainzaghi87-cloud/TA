@extends('customer.layouts.app')

@section('title', 'Blog - Fashion Tips & Trends')

@push('styles')
<style>
    /* Hero Section */
    .hero-gradient {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    }

    /* Blog Card */
    .blog-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-color: #FAD470;
    }

    .blog-card:hover .blog-image {
        transform: scale(1.05);
    }

    /* Blog Image */
    .blog-image {
        transition: transform 0.5s ease;
    }

    /* Category Badge */
    .category-badge {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    }

    /* Featured Post */
    .featured-post {
        background: linear-gradient(135deg, #fff 0%, #fef3c7 100%);
        border: 2px solid #fcd34d;
    }

    /* Search Input */
    .search-input {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .search-input:focus {
        border-color: #FAD470;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(250, 212, 112, 0.1);
    }

    /* Category Filter */
    .category-filter {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .category-filter:hover,
    .category-filter.active {
        border-color: #FAD470;
        background: #fffbeb;
    }

    /* Sidebar Widget */
    .sidebar-widget {
        background: #fff;
        border: 1px solid #e5e7eb;
    }

    /* Tag Cloud */
    .tag-cloud a {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .tag-cloud a:hover {
        border-color: #FAD470;
        background: #fffbeb;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="hero-gradient py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6">
                    Fashion Blog
                </h1>
                <p class="text-xl text-gray-700 max-w-3xl mx-auto mb-8">
                    Discover the latest fashion trends, style tips, and industry insights from our expert writers.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#latest-posts"
                       class="inline-flex items-center justify-center px-8 py-4 bg-gray-900 text-white font-semibold rounded-xl hover:bg-gray-800 transition-colors">
                        <i class="fas fa-book-open mr-2"></i>
                        Read Latest Articles
                    </a>
                    <a href="#newsletter"
                       class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-900 text-gray-900 font-semibold rounded-xl hover:bg-gray-900 hover:text-white transition-colors">
                        <i class="fas fa-envelope mr-2"></i>
                        Subscribe
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Post -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Article</h2>
            </div>

            <div class="featured-post rounded-2xl overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="aspect-video lg:aspect-square overflow-hidden">
                        <img src="https://via.placeholder.com/600x400/fbbf24/1f2937?text=Featured+Article"
                             alt="Featured Article"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="p-8 lg:p-12 flex flex-col justify-center">
                        <div class="mb-4">
                            <span class="category-badge text-white text-xs font-semibold px-3 py-1 rounded-full">
                                Featured
                            </span>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">
                            The Ultimate Guide to Sustainable Fashion in 2024
                        </h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Discover how to build a sustainable wardrobe without compromising on style. Learn about eco-friendly materials, ethical brands, and practical tips for reducing your fashion footprint.
                        </p>
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-amber-200 rounded-full flex items-center justify-center">
                                    <span class="text-amber-700 font-semibold text-sm">JD</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">John Doe</div>
                                    <div class="text-xs text-gray-500">Dec 15, 2024 • 5 min read</div>
                                </div>
                            </div>
                            <div class="flex items-center text-gray-500 text-sm">
                                <i class="fas fa-eye mr-1"></i>
                                2.5k views
                            </div>
                        </div>
                        <a href="#" class="inline-flex items-center text-amber-600 font-semibold hover:text-amber-700 transition-colors">
                            Read Full Article
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Content -->
    <section id="latest-posts" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Latest Articles</h2>
                        <div class="flex items-center space-x-4">
                            <select class="category-filter px-4 py-2 rounded-lg text-sm">
                                <option>All Categories</option>
                                <option>Fashion Trends</option>
                                <option>Style Tips</option>
                                <option>Sustainability</option>
                                <option>Beauty</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Blog Post 1 -->
                        <article class="blog-card rounded-2xl overflow-hidden">
                            <div class="aspect-video overflow-hidden">
                                <img src="https://via.placeholder.com/400x250/f3f4f6/6b7280?text=Winter+Fashion"
                                     alt="Winter Fashion"
                                     class="blog-image w-full h-full object-cover">
                            </div>
                            <div class="p-6">
                                <div class="mb-3">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                        Fashion Trends
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">
                                    Winter 2024: Essential Pieces Every Wardrobe Needs
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm line-clamp-3">
                                    From cozy sweaters to stylish boots, discover the must-have pieces that will keep you warm and fashionable this winter season.
                                </p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center space-x-2">
                                        <img src="https://via.placeholder.com/32x32/fbbf24/1f2937?text=S"
                                             alt="Sarah"
                                             class="w-8 h-8 rounded-full">
                                        <span>Sarah Miller • Dec 14</span>
                                    </div>
                                    <span>4 min read</span>
                                </div>
                            </div>
                        </article>

                        <!-- Blog Post 2 -->
                        <article class="blog-card rounded-2xl overflow-hidden">
                            <div class="aspect-video overflow-hidden">
                                <img src="https://via.placeholder.com/400x250/f3f4f6/6b7280?text=Minimalist+Style"
                                     alt="Minimalist Style"
                                     class="blog-image w-full h-full object-cover">
                            </div>
                            <div class="p-6">
                                <div class="mb-3">
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                        Style Tips
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">
                                    Mastering Minimalist Style: Less is More
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm line-clamp-3">
                                    Learn how to create stunning outfits with minimal pieces. Discover the art of curated simplicity and timeless elegance.
                                </p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center space-x-2">
                                        <img src="https://via.placeholder.com/32x32/fbbf24/1f2937?text=M"
                                             alt="Michael"
                                             class="w-8 h-8 rounded-full">
                                        <span>Michael Chen • Dec 13</span>
                                    </div>
                                    <span>6 min read</span>
                                </div>
                            </div>
                        </article>

                        <!-- Blog Post 3 -->
                        <article class="blog-card rounded-2xl overflow-hidden">
                            <div class="aspect-video overflow-hidden">
                                <img src="https://via.placeholder.com/400x250/f3f4f6/6b7280?text=Color+Theory"
                                     alt="Color Theory"
                                     class="blog-image w-full h-full object-cover">
                            </div>
                            <div class="p-6">
                                <div class="mb-3">
                                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                        Fashion Basics
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">
                                    Color Theory 101: Dress for Your Skin Tone
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm line-clamp-3">
                                    Unlock the secrets to choosing colors that complement your natural skin tone and enhance your best features.
                                </p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center space-x-2">
                                        <img src="https://via.placeholder.com/32x32/fbbf24/1f2937?text=E"
                                             alt="Emily"
                                             class="w-8 h-8 rounded-full">
                                        <span>Emily Davis • Dec 12</span>
                                    </div>
                                    <span>5 min read</span>
                                </div>
                            </div>
                        </article>

                        <!-- Blog Post 4 -->
                        <article class="blog-card rounded-2xl overflow-hidden">
                            <div class="aspect-video overflow-hidden">
                                <img src="https://via.placeholder.com/400x250/f3f4f6/6b7280?text=Sustainable+Fashion"
                                     alt="Sustainable Fashion"
                                     class="blog-image w-full h-full object-cover">
                            </div>
                            <div class="p-6">
                                <div class="mb-3">
                                    <span class="inline-block px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                        Sustainability
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">
                                    10 Ways to Build a More Sustainable Wardrobe
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm line-clamp-3">
                                    Simple yet effective tips to make your fashion choices more environmentally friendly without breaking the bank.
                                </p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center space-x-2">
                                        <img src="https://via.placeholder.com/32x32/fbbf24/1f2937?text=A"
                                             alt="Alex"
                                             class="w-8 h-8 rounded-full">
                                        <span>Alex Johnson • Dec 11</span>
                                    </div>
                                    <span>7 min read</span>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!-- Load More Button -->
                    <div class="text-center mt-12">
                        <button class="inline-flex items-center px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition-colors">
                            Load More Articles
                            <i class="fas fa-arrow-down ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Search Widget -->
                    <div class="sidebar-widget rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Search</h3>
                        <div class="relative">
                            <input type="text"
                                   placeholder="Search articles..."
                                   class="search-input w-full pl-10 pr-4 py-3 rounded-lg">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Categories Widget -->
                    <div class="sidebar-widget rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Categories</h3>
                        <div class="space-y-2">
                            <a href="#" class="flex items-center justify-between py-2 text-gray-700 hover:text-amber-600 transition-colors">
                                <span class="text-sm">Fashion Trends</span>
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">24</span>
                            </a>
                            <a href="#" class="flex items-center justify-between py-2 text-gray-700 hover:text-amber-600 transition-colors">
                                <span class="text-sm">Style Tips</span>
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">18</span>
                            </a>
                            <a href="#" class="flex items-center justify-between py-2 text-gray-700 hover:text-amber-600 transition-colors">
                                <span class="text-sm">Sustainability</span>
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">12</span>
                            </a>
                            <a href="#" class="flex items-center justify-between py-2 text-gray-700 hover:text-amber-600 transition-colors">
                                <span class="text-sm">Fashion Basics</span>
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">15</span>
                            </a>
                            <a href="#" class="flex items-center justify-between py-2 text-gray-700 hover:text-amber-600 transition-colors">
                                <span class="text-sm">Beauty</span>
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">8</span>
                            </a>
                        </div>
                    </div>

                    <!-- Popular Posts Widget -->
                    <div class="sidebar-widget rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Popular Posts</h3>
                        <div class="space-y-4">
                            <a href="#" class="flex space-x-3 group">
                                <img src="https://via.placeholder.com/80x80/f3f4f6/6b7280?text=Popular+1"
                                     alt="Popular post"
                                     class="w-20 h-20 rounded-lg object-cover group-hover:opacity-75 transition-opacity">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 group-hover:text-amber-600 transition-colors line-clamp-2">
                                        5 Wardrobe Essentials Under $50
                                    </h4>
                                    <span class="text-xs text-gray-500">Dec 10 • 3 min</span>
                                </div>
                            </a>
                            <a href="#" class="flex space-x-3 group">
                                <img src="https://via.placeholder.com/80x80/f3f4f6/6b7280?text=Popular+2"
                                     alt="Popular post"
                                     class="w-20 h-20 rounded-lg object-cover group-hover:opacity-75 transition-opacity">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 group-hover:text-amber-600 transition-colors line-clamp-2">
                                        How to Mix Patterns Like a Pro
                                    </h4>
                                    <span class="text-xs text-gray-500">Dec 8 • 5 min</span>
                                </div>
                            </a>
                            <a href="#" class="flex space-x-3 group">
                                <img src="https://via.placeholder.com/80x80/f3f4f6/6b7280?text=Popular+3"
                                     alt="Popular post"
                                     class="w-20 h-20 rounded-lg object-cover group-hover:opacity-75 transition-opacity">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 group-hover:text-amber-600 transition-colors line-clamp-2">
                                        The Ultimate Shoe Guide 2024
                                    </h4>
                                    <span class="text-xs text-gray-500">Dec 5 • 7 min</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Tags Widget -->
                    <div class="sidebar-widget rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Popular Tags</h3>
                        <div class="tag-cloud flex flex-wrap gap-2">
                            <a href="#" class="px-3 py-1 rounded-full text-xs text-gray-600 hover:text-amber-600">fashion</a>
                            <a href="#" class="px-3 py-1 rounded-full text-xs text-gray-600 hover:text-amber-600">style</a>
                            <a href="#" class="px-3 py-1 rounded-full text-xs text-gray-600 hover:text-amber-600">trends</a>
                            <a href="#" class="px-3 py-1 rounded-full text-xs text-gray-600 hover:text-amber-600">minimalist</a>
                            <a href="#" class="px-3 py-1 rounded-full text-xs text-gray-600 hover:text-amber-600">sustainable</a>
                            <a href="#" class="px-3 py-1 rounded-full text-xs text-gray-600 hover:text-amber-600">wardrobe</a>
                            <a href="#" class="px-3 py-1 rounded-full text-xs text-gray-600 hover:text-amber-600">casual</a>
                            <a href="#" class="px-3 py-1 rounded-full text-xs text-gray-600 hover:text-amber-600">formal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section id="newsletter" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="mb-8">
                <i class="fas fa-envelope text-6xl text-amber-500 mb-6"></i>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Stay Fashion Forward
                </h2>
                <p class="text-lg text-gray-600">
                    Get our weekly newsletter delivered to your inbox. Style tips, trends, and exclusive content!
                </p>
            </div>
            <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                <input type="email"
                       placeholder="Enter your email"
                       class="flex-1 px-6 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-amber-500 transition-colors">
                <button type="submit"
                        class="px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition-colors">
                    Subscribe
                </button>
            </form>
            <p class="text-sm text-gray-500 mt-4">
                Join 10,000+ fashion enthusiasts. No spam, unsubscribe anytime.
            </p>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
// Newsletter form handling
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = this.querySelector('input[type="email"]').value;

    if (email) {
        alert('Thank you for subscribing! Check your email for confirmation.');
        this.querySelector('input[type="email"]').value = '';
    }
});
</script>
@endpush