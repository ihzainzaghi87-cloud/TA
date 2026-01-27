@extends('customer.layouts.app')

@section('title', 'Blog')

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }

    /* Animation for floating elements */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    .animate-bounce-slow { animation: float 6s ease-in-out infinite; }

    /* Article Card Styles */
    .article-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 1.5rem;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .article-card:hover {
        border-color: #1A1A1D;
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .article-card .article-image {
        transition: transform 0.5s ease;
    }

    .article-card:hover .article-image {
        transform: scale(1.05);
    }

    /* Featured Article Card */
    .featured-article {
        position: relative;
        border-radius: 1.5rem;
        overflow: hidden;
    }

    .featured-article::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 50%, transparent 100%);
        z-index: 1;
    }

    /* Pagination Styles */
    .pagination-link {
        transition: all 0.2s ease;
    }

    .pagination-link:hover {
        background: #1A1A1D;
        color: #fff;
        border-color: #1A1A1D;
    }

    .pagination-link.active {
        background: #1A1A1D;
        border-color: #1A1A1D;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 pt-8">

    {{-- Breadcrumb --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex items-center text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-black transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-gray-900 font-medium">Blog</span>
        </nav>
    </div>

    {{-- Featured Article --}}
    @if($articles->isNotEmpty() && $articles->currentPage() == 1)
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        @php $featuredArticle = $articles->first(); @endphp
        <a href="{{ route('articles.show', $featuredArticle->slug) }}" class="block featured-article group">
            <div class="relative h-[400px] md:h-[500px] overflow-hidden rounded-[2rem] border border-gray-200 shadow-sm">
                {{-- Article Image or Placeholder --}}
                @if($featuredArticle->thumbnail)
                    <img src="{{ asset('storage/' . $featuredArticle->thumbnail) }}"
                         alt="{{ $featuredArticle->title }}"
                         class="w-full h-full object-cover article-image">
                @else
                    <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                        <i class="fas fa-newspaper text-white text-8xl opacity-50"></i>
                    </div>
                @endif

                {{-- Content Overlay --}}
                <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12 z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-white text-black px-4 py-1 rounded-full text-sm font-bold">
                            Featured
                        </span>
                        <span class="text-white/90 text-sm font-medium">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ $featuredArticle->published_at ? $featuredArticle->published_at->format('M d, Y') : $featuredArticle->created_at->format('M d, Y') }}
                        </span>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-bold text-white mb-4 group-hover:underline decoration-2 underline-offset-4 transition-all">
                        {{ $featuredArticle->title }}
                    </h2>
                    <p class="text-white/80 text-lg max-w-3xl line-clamp-2 mb-6">
                        {{ Str::limit(strip_tags($featuredArticle->content), 200) }}
                    </p>
                    <div class="inline-flex items-center text-white font-bold text-lg group-hover:gap-3 transition-all">
                        Read Blog <i class="fas fa-arrow-right ml-2"></i>
                    </div>
                </div>
            </div>
        </a>
    </section>
    @endif

    {{-- Blog Grid --}}
    <section id="latest-posts" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="flex justify-between items-end mb-10 border-b border-gray-200 pb-4">
            <div>
                <h2 class="text-3xl font-black text-gray-900">
                    Latest Posts
                </h2>
                <p class="text-gray-500 mt-1">Explore our latest fashion insights</p>
            </div>
            <div class="text-gray-900 font-medium bg-gray-100 px-4 py-1 rounded-full text-sm">
                {{ $articles->total() }} Blogs
            </div>
        </div>

        @if($articles->isEmpty())
            {{-- Empty State --}}
            <div class="text-center py-20 bg-white rounded-[1.5rem] border border-gray-100 shadow-lg">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-pen-fancy text-gray-300 text-4xl"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">No Blogs Yet</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">We are currently crafting amazing content for you. Please check back later!</p>
                <a href="{{ route('home') }}"
                   class="inline-block bg-[#1A1A1D] text-white px-8 py-3 rounded-full font-black hover:bg-gray-800 transition-colors">
                    Back to Home
                </a>
            </div>
        @else
            {{-- Blog Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($articles->skip($articles->currentPage() == 1 ? 1 : 0) as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="article-card group flex flex-col h-full">
                    {{-- Blog Image --}}
                    <div class="relative h-56 overflow-hidden bg-gray-100">
                        @if($article->thumbnail)
                            <img src="{{ asset('storage/' . $article->thumbnail) }}"
                                 alt="{{ $article->title }}"
                                 class="w-full h-full object-cover article-image">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i class="fas fa-image text-4xl"></i>
                            </div>
                        @endif

                        <div class="absolute top-4 left-4">
                            <span class="bg-white text-black text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                Blog
                            </span>
                        </div>
                    </div>

                    {{-- Article Content --}}
                    <div class="p-6 flex-1 flex flex-col">
                        {{-- Date --}}
                        <div class="flex items-center gap-2 text-gray-400 text-xs font-medium mb-3 uppercase tracking-wide">
                            {{ $article->published_at ? $article->published_at->format('F d, Y') : $article->created_at->format('F d, Y') }}
                        </div>

                        {{-- Title --}}
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:underline decoration-2 underline-offset-4 decoration-black">
                            {{ $article->title }}
                        </h3>

                        {{-- Excerpt --}}
                        <p class="text-gray-600 text-sm line-clamp-3 mb-6 flex-1">
                            {{ Str::limit(strip_tags($article->content), 120) }}
                        </p>

                        {{-- Read More --}}
                        <div class="flex items-center text-black font-bold text-sm mt-auto group-hover:gap-2 transition-all">
                            Read More
                            <i class="fas fa-arrow-right ml-2 text-xs"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($articles->hasPages())
            <div class="mt-16 flex justify-center">
                <nav class="flex items-center gap-2">
                    {{-- Previous --}}
                    @if($articles->onFirstPage())
                        <span class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 text-gray-300 cursor-not-allowed">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </span>
                    @else
                        <a href="{{ $articles->previousPageUrl() }}"
                           class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 text-black hover:bg-[#1A1A1D] hover:text-white transition-colors">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                        @if($page == $articles->currentPage())
                            <span class="w-10 h-10 flex items-center justify-center rounded-full bg-[#1A1A1D] text-white font-black text-sm shadow-lg">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="w-10 h-10 flex items-center justify-center rounded-full text-gray-600 hover:bg-gray-100 font-medium text-sm transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($articles->hasMorePages())
                        <a href="{{ $articles->nextPageUrl() }}"
                           class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 text-black hover:bg-[#1A1A1D] hover:text-white transition-colors">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    @else
                        <span class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 text-gray-300 cursor-not-allowed">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </span>
                    @endif
                </nav>
            </div>
            @endif
        @endif
    </section>
</div>
@endsection
