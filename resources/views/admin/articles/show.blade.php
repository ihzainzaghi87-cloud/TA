@extends('admin.layouts.app')

@section('title', 'View Article')

@section('content')
<style>
    /* Hide Trix attachment captions */
    .article-content .attachment__caption,
    .article-content figcaption.attachment__caption {
        display: none !important;
    }
    
    /* Style attachment images */
    .article-content .attachment {
        margin: 2rem 0;
    }
    
    .article-content .attachment img {
        width: 100%;
        max-width: 100%;
        height: auto;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
    }
    
    /* Fix Lists (Bullets & Numbers) */
    .article-content ul {
        list-style-type: disc !important;
        padding-left: 1.5rem !important;
        margin: 1rem 0 !important;
    }
    
    .article-content ol {
        list-style-type: decimal !important;
        padding-left: 1.5rem !important;
        margin: 1rem 0 !important;
    }
    
    .article-content ul li,
    .article-content ol li {
        margin: 0.5rem 0 !important;
        padding-left: 0.25rem !important;
        display: list-item !important;
    }
    
    .article-content ul li::marker {
        color: #059669 !important;
    }
    
    .article-content ol li::marker {
        color: #059669 !important;
        font-weight: 600;
    }
    
    /* Blockquote styling */
    .article-content blockquote {
        border-left: 4px solid #059669 !important;
        padding: 1rem !important;
        margin: 1.5rem 0 !important;
        background: #f0fdf4 !important;
        border-radius: 0.5rem !important;
        font-style: italic;
        color: #4b5563 !important;
    }
    
    /* Inline Code */
    .article-content code {
        background-color: #f3f4f6 !important;
        color: #059669 !important;
        padding: 0.2rem 0.4rem !important;
        border-radius: 0.25rem !important;
        font-size: 0.875em !important;
        font-weight: 600 !important;
        font-family: 'Courier New', Courier, monospace !important;
        border: 1px solid #e5e7eb !important;
    }
    
    /* Code Block / Pre */
    .article-content pre {
        background-color: #1e293b !important;
        color: #e2e8f0 !important;
        padding: 1rem !important;
        border-radius: 0.5rem !important;
        overflow-x: auto !important;
        margin: 1.5rem 0 !important;
        border: 1px solid #334155 !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Code inside Pre */
    .article-content pre code {
        background-color: transparent !important;
        color: #e2e8f0 !important;
        padding: 0 !important;
        border: none !important;
        font-size: 0.875rem !important;
        font-weight: normal !important;
        line-height: 1.7 !important;
    }
    
    /* Headings */
    .article-content h1 {
        font-size: 2rem !important;
        font-weight: 800 !important;
        margin-top: 2rem !important;
        margin-bottom: 1rem !important;
        line-height: 1.2 !important;
        color: #111827 !important;
    }
    
    .article-content h2 {
        font-size: 1.5rem !important;
        font-weight: 700 !important;
        margin-top: 1.75rem !important;
        margin-bottom: 0.875rem !important;
        line-height: 1.3 !important;
        color: #111827 !important;
    }
    
    .article-content h3 {
        font-size: 1.25rem !important;
        font-weight: 600 !important;
        margin-top: 1.5rem !important;
        margin-bottom: 0.75rem !important;
        line-height: 1.4 !important;
        color: #111827 !important;
    }
    
    /* Paragraphs */
    .article-content p {
        margin: 1rem 0 !important;
        line-height: 1.75 !important;
        color: #374151 !important;
    }
    
    /* Strong/Bold */
    .article-content strong {
        font-weight: 700 !important;
        color: #111827 !important;
    }
    
    /* Emphasis/Italic */
    .article-content em {
        font-style: italic !important;
    }
    
    /* Links */
    .article-content a {
        color: #059669 !important;
        text-decoration: underline !important;
        text-underline-offset: 2px !important;
    }
    
    .article-content a:hover {
        color: #047857 !important;
        text-decoration: none !important;
    }
    
    /* Horizontal Rule */
    .article-content hr {
        border: none !important;
        border-top: 2px solid #e5e7eb !important;
        margin: 2rem 0 !important;
    }
    
    /* Dark mode */
    .dark .article-content .attachment img {
        border-color: #374151;
    }
    
    .dark .article-content blockquote {
        background: #064e3b !important;
        color: #9ca3af !important;
        border-left-color: #34d399 !important;
    }
    
    .dark .article-content code {
        background-color: #1f2937 !important;
        color: #34d399 !important;
        border-color: #374151 !important;
    }
    
    .dark .article-content pre {
        background-color: #0f172a !important;
        border-color: #1e293b !important;
    }
    
    .dark .article-content h1,
    .dark .article-content h2,
    .dark .article-content h3,
    .dark .article-content strong {
        color: #f9fafb !important;
    }
    
    .dark .article-content p {
        color: #d1d5db !important;
    }
    
    .dark .article-content a {
        color: #34d399 !important;
    }
    
    .dark .article-content a:hover {
        color: #10b981 !important;
    }
    
    .dark .article-content hr {
        border-top-color: #374151 !important;
    }
    
    .dark .article-content ul li::marker,
    .dark .article-content ol li::marker {
        color: #34d399 !important;
    }
</style>

<div class="space-y-6">
    <!-- Header Section - Compact -->
    <div class="relative bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-800 dark:to-emerald-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-xl font-bold mb-1">{{ $article->title }}</h1>
                    <p class="text-green-100 text-sm">
                        Published {{ $article->published_at ? $article->published_at->format('d M Y') : 'Not published yet' }}
                        · Created {{ $article->created_at->diffForHumans() }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.articles.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Article Details Card -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700">
        <!-- Article Meta Info -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
                        @if ($article->is_published)
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Published
                        </span>
                        @else
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Draft
                        </span>
                        @endif
                    </div>
                </div>
                
                <div class="flex gap-2">
                    @can('articles.update')
                    <a href="{{ route('admin.articles.edit', $article) }}" 
                       class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg text-xs font-medium text-white hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    @endcan

                    @can('articles.delete')
                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this article?')">
                        @csrf @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-600 to-rose-600 border border-transparent rounded-lg text-xs font-medium text-white hover:from-red-700 hover:to-rose-700 transition-all duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Article Content -->
        <div class="p-6">
            <div class="article-content">
                @if($article->trixRichText->isNotEmpty())
                    {!! $article->trixRender('content') !!}
                @else
                    {!! $article->content !!}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
