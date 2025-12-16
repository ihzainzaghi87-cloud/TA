@extends('customer.layouts.app')

@section('title', $article->title)

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }

    /* Article Content Styles */
    .article-content {
        font-size: 1.125rem;
        line-height: 1.8;
        color: #374151;
    }

    .article-content p {
        margin-bottom: 1.5rem;
    }

    .article-content h1,
    .article-content h2,
    .article-content h3,
    .article-content h4,
    .article-content h5,
    .article-content h6 {
        font-weight: 700;
        color: #111827;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .article-content h2 {
        font-size: 1.75rem;
    }

    .article-content h3 {
        font-size: 1.5rem;
    }

    .article-content h4 {
        font-size: 1.25rem;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 16px;
        margin: 2rem auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        display: block;
    }

    .article-content a {
        color: #F59E0B;
        text-decoration: underline;
        transition: color 0.2s ease;
    }

    .article-content a:hover {
        color: #D97706;
    }

    .article-content ul,
    .article-content ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }

    .article-content li {
        margin-bottom: 0.5rem;
    }

    .article-content ul li {
        list-style-type: disc;
    }

    .article-content ol li {
        list-style-type: decimal;
    }

    .article-content blockquote {
        border-left: 4px solid #FAD470;
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #6B7280;
        background: #FFFBEB;
        padding: 1.5rem;
        border-radius: 0 16px 16px 0;
    }

    .article-content pre,
    .article-content code {
        background: #F3F4F6;
        border-radius: 8px;
        padding: 0.25rem 0.5rem;
        font-family: 'Courier New', monospace;
    }

    .article-content pre {
        padding: 1.5rem;
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    /* Hide Trix attachment captions (filename, filesize) */
    .article-content figure.attachment {
        margin: 2rem 0;
    }

    .article-content figure.attachment figcaption {
        display: none !important;
    }

    .article-content .attachment__name,
    .article-content .attachment__size,
    .article-content .attachment__caption {
        display: none !important;
    }

    /* Related Article Card */
    .related-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .related-card:hover {
        border-color: #FAD470;
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    }

    .related-card .card-image {
        transition: transform 0.5s ease;
    }

    .related-card:hover .card-image {
        transform: scale(1.05);
    }

    /* Share Button Styles */
    .share-btn {
        transition: all 0.2s ease;
    }

    .share-btn:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Article Header with Image --}}
    <section class="relative">
        {{-- Header Background --}}
        <div class="relative h-[400px] md:h-[500px] overflow-hidden">
            @if($article->trixRichText && $article->trixRichText->first())
                @php
                    preg_match('/<img[^>]+src="([^">]+)"/', $article->trixRichText->first()->content ?? '', $matches);
                    $imageUrl = $matches[1] ?? null;
                @endphp
                @if($imageUrl)
                    <img src="{{ $imageUrl }}" 
                         alt="{{ $article->title }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                @else
                    <div class="w-full h-full bg-gradient-to-br from-yellow-400 to-yellow-600">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                @endif
            @else
                <div class="w-full h-full bg-gradient-to-br from-yellow-400 to-yellow-600">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                </div>
            @endif

            {{-- Header Content --}}
            <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12">
                <div class="max-w-4xl mx-auto">
                    {{-- Breadcrumb --}}
                    <nav class="flex items-center text-sm mb-6">
                        <a href="{{ route('home') }}" class="text-white/70 hover:text-white transition-colors">
                            <i class="fas fa-home"></i>
                        </a>
                        <i class="fas fa-chevron-right text-white/50 mx-3 text-xs"></i>
                        <a href="{{ route('articles.index') }}" class="text-white/70 hover:text-white transition-colors">
                            Articles
                        </a>
                        <i class="fas fa-chevron-right text-white/50 mx-3 text-xs"></i>
                        <span class="text-white truncate max-w-[200px]">{{ $article->title }}</span>
                    </nav>

                    {{-- Article Meta --}}
                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        <span class="bg-[#FAD470] text-black px-4 py-1 rounded-full text-sm font-semibold">
                            Article
                        </span>
                        <span class="text-white/80 text-sm flex items-center gap-2">
                            <i class="far fa-calendar-alt"></i>
                            {{ $article->published_at ? $article->published_at->format('F d, Y') : $article->created_at->format('F d, Y') }}
                        </span>
                        <span class="text-white/80 text-sm flex items-center gap-2">
                            <i class="far fa-clock"></i>
                            {{ ceil(str_word_count(strip_tags($article->content)) / 200) }} min read
                        </span>
                    </div>

                    {{-- Title --}}
                    <h1 class="text-3xl md:text-5xl font-bold text-white leading-tight">
                        {{ $article->title }}
                    </h1>
                </div>
            </div>
        </div>
    </section>

    {{-- Article Content --}}
    <section class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Back to Articles --}}
            <a href="{{ route('articles.index') }}" 
               class="inline-flex items-center gap-2 text-gray-600 hover:text-amber-600 transition-colors mb-8">
                <i class="fas fa-arrow-left"></i>
                <span>Back to all articles</span>
            </a>

            {{-- Main Content Card --}}
            <article class="bg-white rounded-2xl shadow-sm p-8 md:p-12">
                {{-- Article Body --}}
                <div class="article-content prose prose-lg max-w-none">
                    {!! $article->content !!}
                </div>

                {{-- Share Section --}}
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Share this article</h4>
                    <div class="flex flex-wrap gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                           target="_blank"
                           class="share-btn w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}" 
                           target="_blank"
                           class="share-btn w-12 h-12 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . request()->url()) }}" 
                           target="_blank"
                           class="share-btn w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <button onclick="copyToClipboard('{{ request()->url() }}')"
                                class="share-btn w-12 h-12 bg-gray-700 text-white rounded-full flex items-center justify-center hover:bg-gray-800">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between gap-4">
                        @if($previousArticle)
                        <a href="{{ route('articles.show', $previousArticle->slug) }}" 
                           class="flex items-center gap-3 text-gray-600 hover:text-amber-600 transition-colors group">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                            <div>
                                <span class="text-sm text-gray-400">Previous</span>
                                <p class="font-semibold line-clamp-1">{{ Str::limit($previousArticle->title, 30) }}</p>
                            </div>
                        </a>
                        @else
                        <div></div>
                        @endif

                        @if($nextArticle)
                        <a href="{{ route('articles.show', $nextArticle->slug) }}" 
                           class="flex items-center gap-3 text-gray-600 hover:text-amber-600 transition-colors group text-right">
                            <div>
                                <span class="text-sm text-gray-400">Next</span>
                                <p class="font-semibold line-clamp-1">{{ Str::limit($nextArticle->title, 30) }}</p>
                            </div>
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
            </article>
        </div>
    </section>

    {{-- More Articles Section --}}
    @if($relatedArticles->isNotEmpty())
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                    More Articles
                </h2>
                <a href="{{ route('articles.index') }}" 
                   class="inline-flex items-center text-amber-600 hover:text-amber-700 font-semibold">
                    View All <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($relatedArticles->take(3) as $related)
                <a href="{{ route('articles.show', $related->slug) }}" class="related-card group">
                    {{-- Image --}}
                    <div class="relative h-48 overflow-hidden">
                        @if($related->trixRichText && $related->trixRichText->first())
                            @php
                                preg_match('/<img[^>]+src="([^">]+)"/', $related->trixRichText->first()->content ?? '', $relatedMatches);
                                $relatedImageUrl = $relatedMatches[1] ?? null;
                            @endphp
                            @if($relatedImageUrl)
                                <img src="{{ $relatedImageUrl }}" 
                                     alt="{{ $related->title }}" 
                                     class="w-full h-full object-cover card-image">
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

                    {{-- Content --}}
                    <div class="p-6">
                        <div class="text-gray-500 text-sm mb-2">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ $related->published_at ? $related->published_at->format('M d, Y') : $related->created_at->format('M d, Y') }}
                        </div>
                        <h3 class="font-bold text-gray-900 line-clamp-2 group-hover:text-amber-600 transition-colors">
                            {{ $related->title }}
                        </h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Link copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endpush
@endsection
