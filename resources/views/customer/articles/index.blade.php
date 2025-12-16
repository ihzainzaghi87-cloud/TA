@extends('customer.layouts.app')

@section('title', 'Articles')

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }

    /* Article Card Styles */
    .article-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .article-card:hover {
        border-color: #FAD470;
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
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
        border-radius: 24px;
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
        background: #FAD470;
        border-color: #FAD470;
    }

    .pagination-link.active {
        background: #FAD470;
        border-color: #FAD470;
        color: #000;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Hero Section --}}
    <section class="relative bg-[#FAD470] py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-5xl md:text-7xl font-black text-black mb-4">
                    ARTICLES
                </h1>
                <p class="text-lg md:text-xl text-gray-800 max-w-2xl mx-auto">
                    Discover the latest trends, tips, and stories from The Paranoia
                </p>
            </div>
        </div>
        {{-- Decorative Elements --}}
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gray-50" style="clip-path: ellipse(70% 100% at 50% 100%);"></div>
    </section>

    {{-- Breadcrumb --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <nav class="flex items-center text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-amber-600 transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-300 mx-3 text-xs"></i>
            <span class="text-gray-900 font-medium">Articles</span>
        </nav>
    </div>

    {{-- Featured Article --}}
    @if($articles->isNotEmpty() && $articles->currentPage() == 1)
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        @php $featuredArticle = $articles->first(); @endphp
        <a href="{{ route('articles.show', $featuredArticle->slug) }}" class="block featured-article group">
            <div class="relative h-[400px] md:h-[500px] overflow-hidden rounded-3xl">
                {{-- Article Image or Placeholder --}}
                @if($featuredArticle->trixRichText && $featuredArticle->trixRichText->first())
                    @php
                        preg_match('/<img[^>]+src="([^">]+)"/', $featuredArticle->trixRichText->first()->content ?? '', $matches);
                        $imageUrl = $matches[1] ?? null;
                    @endphp
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $featuredArticle->title }}" 
                             class="w-full h-full object-cover article-image">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center">
                            <i class="fas fa-newspaper text-white text-8xl opacity-50"></i>
                        </div>
                    @endif
                @else
                    <div class="w-full h-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center">
                        <i class="fas fa-newspaper text-white text-8xl opacity-50"></i>
                    </div>
                @endif

                {{-- Content Overlay --}}
                <div class="absolute bottom-0 left-0 right-0 p-8 z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-[#FAD470] text-black px-4 py-1 rounded-full text-sm font-semibold">
                            Featured
                        </span>
                        <span class="text-white/80 text-sm">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ $featuredArticle->published_at ? $featuredArticle->published_at->format('M d, Y') : $featuredArticle->created_at->format('M d, Y') }}
                        </span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-3 group-hover:text-[#FAD470] transition-colors">
                        {{ $featuredArticle->title }}
                    </h2>
                    <p class="text-white/80 text-lg max-w-2xl line-clamp-2">
                        {{ Str::limit(strip_tags($featuredArticle->content), 200) }}
                    </p>
                    <div class="mt-6 inline-flex items-center text-[#FAD470] font-semibold group-hover:gap-3 transition-all">
                        Read More <i class="fas fa-arrow-right ml-2"></i>
                    </div>
                </div>
            </div>
        </a>
    </section>
    @endif

    {{-- Articles Grid --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                Latest Articles
            </h2>
            <div class="text-gray-600">
                {{ $articles->total() }} {{ Str::plural('article', $articles->total()) }} found
            </div>
        </div>

        @if($articles->isEmpty())
            {{-- Empty State --}}
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-newspaper text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Articles Yet</h3>
                <p class="text-gray-600 mb-6">Check back later for the latest updates and stories.</p>
                <a href="{{ route('home') }}" 
                   class="inline-block bg-[#FAD470] text-black px-6 py-3 rounded-full font-semibold hover:bg-yellow-500 transition-colors">
                    Back to Home
                </a>
            </div>
        @else
            {{-- Articles Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($articles->skip($articles->currentPage() == 1 ? 1 : 0) as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="article-card group">
                    {{-- Article Image --}}
                    <div class="relative h-48 overflow-hidden">
                        @if($article->trixRichText && $article->trixRichText->first())
                            @php
                                preg_match('/<img[^>]+src="([^">]+)"/', $article->trixRichText->first()->content ?? '', $matches);
                                $imageUrl = $matches[1] ?? null;
                            @endphp
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" 
                                     alt="{{ $article->title }}" 
                                     class="w-full h-full object-cover article-image">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <i class="fas fa-newspaper text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Article Content --}}
                    <div class="p-6">
                        {{-- Date --}}
                        <div class="flex items-center gap-2 text-gray-500 text-sm mb-3">
                            <i class="far fa-calendar-alt"></i>
                            {{ $article->published_at ? $article->published_at->format('M d, Y') : $article->created_at->format('M d, Y') }}
                        </div>

                        {{-- Title --}}
                        <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-amber-600 transition-colors">
                            {{ $article->title }}
                        </h3>

                        {{-- Excerpt --}}
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                            {{ Str::limit(strip_tags($article->content), 120) }}
                        </p>

                        {{-- Read More --}}
                        <div class="flex items-center text-amber-600 font-semibold text-sm group-hover:text-amber-700">
                            Read More 
                            <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($articles->hasPages())
            <div class="mt-12 flex justify-center">
                <nav class="flex items-center gap-2">
                    {{-- Previous --}}
                    @if($articles->onFirstPage())
                        <span class="px-4 py-2 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $articles->previousPageUrl() }}" 
                           class="px-4 py-2 text-gray-700 hover:text-amber-600 transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                        @if($page == $articles->currentPage())
                            <span class="px-4 py-2 bg-[#FAD470] text-black font-semibold rounded-lg">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" 
                               class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($articles->hasMorePages())
                        <a href="{{ $articles->nextPageUrl() }}" 
                           class="px-4 py-2 text-gray-700 hover:text-amber-600 transition-colors">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-4 py-2 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </nav>
            </div>
            @endif
        @endif
    </section>
</div>
@endsection
