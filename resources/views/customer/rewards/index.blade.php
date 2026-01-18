@extends('customer.layouts.app')

@section('title', $activeCategory ? $activeCategory->name : 'All Rewards')

@section('content')
<!-- Hero Section -->
<section class="bg-[#1A1A1D]">
    <!-- Text Content -->
    <div class="py-20 px-6 md:px-12">
        <p class="text-sm md:text-base text-white mb-2">
            Home / Rewards
            @if($activeCategory)
                / {{ $activeCategory->name }}
            @endif
        </p>
        <h1 class="text-4xl text-white md:text-5xl font-bold mb-4 text-left">
            @if($activeCategory)
                {{ $activeCategory->name }}
            @else
                Explore Reward Products
            @endif
        </h1>
        <p class="text-lg text-white md:text-xl mb-8">
            {{ $activeCategory ? $activeCategory->description : 'Redeem your points for exclusive reward products!' }}
        </p>
    </div>
</section>

<!-- Products Section -->
<section class="bg-white py-12 px-4 sm:px-6 md:px-12">
    <div class="mb-10 flex flex-col md:flex-row gap-4 items-center justify-between">
        <form method="GET" action="{{ route('rewards') }}" class="flex-1 w-full md:max-w-md">
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            @if(request('price_min')) <input type="hidden" name="price_min" value="{{ request('price_min') }}"> @endif
            @if(request('price_max')) <input type="hidden" name="price_max" value="{{ request('price_max') }}"> @endif
            @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
            
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search reward products..." 
                    class="w-full px-5 py-3 pl-11 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#1A1A1D] focus:border-transparent transition-all placeholder-gray-400 font-medium"
                >
                <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </form>

        <div class="flex gap-3 w-full md:w-auto">
            <button 
                onclick="openFilterModal()" 
                class="flex-1 md:flex-none px-6 py-3 bg-[#1A1A1D] text-white rounded-xl hover:bg-gray-800 transition-all flex items-center justify-center gap-2 font-bold shadow-lg hover:shadow-xl transform active:scale-95"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter
                @if(request('category') || request('price_min') || request('price_max'))
                    <span class="bg-white text-[#1A1A1D] text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ collect([request('category'), request('price_min'), request('price_max')])->filter()->count() }}
                    </span>
                @endif
            </button>

            <form method="GET" action="{{ route('rewards') }}" id="sortForm" class="flex-1 md:flex-none">
                @foreach(request()->except('sort') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                
                <select 
                    name="sort" 
                    onchange="document.getElementById('sortForm').submit()"
                    class="w-full px-6 py-3 border border-gray-200 bg-gray-50 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#1A1A1D] focus:border-transparent font-medium cursor-pointer"
                >
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="bestseller" {{ request('sort') == 'bestseller' ? 'selected' : '' }}>Most Claimed</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Lowest Points</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Highest Points</option>
                </select>
            </form>
        </div>
    </div>

    @if(request('category') || request('price_min') || request('price_max') || request('search'))
        <div class="mb-8 flex flex-wrap gap-2 items-center">
            <span class="text-sm font-bold text-gray-500 mr-2">Filters:</span>
            
            @if(request('search'))
                <a href="{{ route('rewards', array_filter(request()->except('search'))) }}" 
                   class="inline-flex items-center gap-2 px-4 py-1.5 bg-[#1A1A1D] text-white rounded-full text-sm hover:bg-gray-800 transition-colors">
                    "{{ request('search') }}"
                    <i class="fas fa-times text-xs"></i>
                </a>
            @endif

            @if($activeCategory)
                <a href="{{ route('rewards', array_filter(request()->except('category'))) }}" 
                   class="inline-flex items-center gap-2 px-4 py-1.5 bg-gray-200 text-[#1A1A1D] rounded-full text-sm font-bold hover:bg-gray-300 transition-colors">
                    {{ $activeCategory->name }}
                    <i class="fas fa-times text-xs"></i>
                </a>
            @endif

            @if(request('price_min') || request('price_max'))
                <a href="{{ route('rewards', array_filter(request()->except(['price_min', 'price_max']))) }}" 
                   class="inline-flex items-center gap-2 px-4 py-1.5 bg-gray-200 text-[#1A1A1D] rounded-full text-sm font-bold hover:bg-gray-300 transition-colors">
                    {{ number_format(request('price_min', 0)) }} - {{ number_format(request('price_max', 999999)) }} Pts
                    <i class="fas fa-times text-xs"></i>
                </a>
            @endif

            <a href="{{ route('rewards') }}" class="text-sm font-bold text-red-500 hover:text-red-700 underline ml-2">
                Clear All
            </a>
        </div>
    @endif

    <h2 class="text-2xl md:text-3xl font-black text-[#1A1A1D] mb-8">
        {{ $activeCategory ? $activeCategory->name : 'All Rewards' }}
        <span class="text-lg text-gray-500 font-medium ml-2">({{ $products->total() }} items)</span>
    </h2>

    @if($products->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 mb-12">
            @foreach($products as $product)
                <div class="group bg-white rounded-[30px] p-4 hover:shadow-2xl transition-all duration-300 border border-gray-100">
                    
                    <div class="relative w-full h-48 bg-[#F3F5F9] rounded-[20px] overflow-hidden flex items-center justify-center">
                        <a href="{{ route('reward.detail', $product->slug) }}" class="w-full h-full flex items-center justify-center">
                            @if($product->images->isNotEmpty())
                                <img 
                                    src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                    alt="{{ $product->name }}" 
                                    class="w-full h-full object-contain p-5 group-hover:scale-110 transition-transform duration-500 mix-blend-multiply"
                                >
                            @else
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-image text-3xl mb-2"></i>
                                    <span class="text-xs">No Image</span>
                                </div>
                            @endif
                        </a>

                         @if(request('sort') == 'bestseller' && isset($product->total_sold) && $product->total_sold > 0)
                            <div class="absolute top-3 left-3 bg-[#1A1A1D] text-white font-bold text-[10px] px-2.5 py-1 rounded-full shadow-sm z-10">
                                🔥 {{ $product->total_sold }} Claimed
                            </div>
                        @endif
                    </div>
                    
                    <div class="pt-4 pb-1">
                        <div class="mb-2">
                            <span class="text-[11px] font-bold text-[#1A1A1D] bg-gray-100 px-2 py-1 rounded-md uppercase tracking-wide">
                                {{ $product->category->name ?? 'REWARD' }}
                            </span>
                        </div>
                        
                        <a href="{{ route('reward.detail', $product->slug) }}">
                            <h3 class="text-base font-bold text-[#1A1A1D] mb-1 line-clamp-1 group-hover:text-gray-600 transition-colors">
                                {{ $product->name }}
                            </h3>
                        </a>
                        
                        <p class="text-lg font-black text-[#1A1A1D] mb-4 flex items-center gap-1.5">
                            <i class="fas fa-coins text-yellow-500 text-sm"></i>
                            {{ number_format($product->point_price, 0, ',', '.') }} Pts
                        </p>
                        
                        <a href="{{ route('reward.detail', $product->slug) }}" 
                           class="flex items-center justify-center gap-2 w-full bg-[#F3F5F9] text-[#1A1A1D] font-bold py-2.5 rounded-xl hover:bg-[#1A1A1D] hover:text-white active:scale-95 transition-all duration-200">
                            <i class="fas fa-gift text-sm"></i>
                            <span class="text-sm">Redeem</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($products->hasPages())
        <div class="mt-12 flex flex-col items-center gap-4">
            <div class="flex items-center gap-2">
                {{-- Previous --}}
                @if ($products->onFirstPage())
                    <span class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-300 cursor-not-allowed">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" 
                       class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-300 text-[#1A1A1D] hover:bg-[#1A1A1D] hover:text-white hover:border-[#1A1A1D] transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach (range(1, $products->lastPage()) as $page)
                    @if ($page == $products->currentPage())
                        <span class="w-10 h-10 flex items-center justify-center rounded-full bg-[#1A1A1D] text-white font-bold shadow-lg transform scale-110">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $products->url($page) }}" 
                           class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-300 text-[#1A1A1D] hover:bg-[#1A1A1D] hover:text-white hover:border-[#1A1A1D] transition-all duration-200 font-bold">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" 
                       class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-300 text-[#1A1A1D] hover:bg-[#1A1A1D] hover:text-white hover:border-[#1A1A1D] transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </a>
                @else
                    <span class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-300 cursor-not-allowed">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </span>
                @endif
            </div>

            <p class="text-sm text-gray-500 font-medium">
                Page <span class="text-[#1A1A1D] font-bold">{{ $products->currentPage() }}</span> 
                of 
                <span class="text-[#1A1A1D] font-bold">{{ $products->lastPage() }}</span>
            </p>
        </div>
        @endif
    @else
        <div class="text-center py-20 bg-gray-50 rounded-[30px] border border-gray-100">
            <div class="bg-white w-24 h-24 mx-auto rounded-full flex items-center justify-center shadow-sm mb-6">
                <i class="fas fa-gift text-gray-300 text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-[#1A1A1D] mb-2">No Reward Products Found</h3>
            <p class="text-gray-500 mb-8 max-w-xs mx-auto">We couldn't find any rewards matching your search filters.</p>
            <a href="{{ route('rewards') }}" class="inline-block px-8 py-3 bg-[#1A1A1D] text-white font-bold rounded-xl hover:bg-gray-800 transition-all shadow-lg hover:shadow-xl">
                Clear Filters
            </a>
        </div>
    @endif
</section>

<!-- Filter Modal -->
<div id="filterModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-900">Filter Products</h3>
            <button onclick="closeFilterModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form method="GET" action="{{ route('rewards') }}" id="filterForm">
            <!-- Preserve search and sort -->
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif

            <div class="p-6 space-y-6">
                <!-- Category Filter -->
                <div>
                    <label class="block text-lg font-semibold text-gray-900 mb-3">Category</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($categories as $category)
                            <label class="flex items-center space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition {{ request('category') == $category->id ? 'border-orange-500 bg-orange-50' : 'border-gray-300' }}">
                                <input 
                                    type="radio" 
                                    name="category" 
                                    value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'checked' : '' }}
                                    class="w-4 h-4 text-orange-600 focus:ring-orange-500"
                                >
                                <span class="text-sm font-medium text-gray-900">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <label class="flex items-center space-x-3 p-3 mt-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input 
                            type="radio" 
                            name="category" 
                            value=""
                            {{ !request('category') ? 'checked' : '' }}
                            class="w-4 h-4 text-orange-600 focus:ring-orange-500"
                        >
                        <span class="text-sm font-medium text-gray-900">All Categories</span>
                    </label>
                </div>

                <!-- Price Range Filter -->
                <div>
                    <label class="block text-lg font-semibold text-gray-900 mb-3">Points Range</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Min Points</label>
                            <input 
                                type="number" 
                                name="price_min" 
                                value="{{ request('price_min', $priceRange->min_price ?? 0) }}"
                                min="0"
                                placeholder="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            >
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Max Points</label>
                            <input 
                                type="number" 
                                name="price_max" 
                                value="{{ request('price_max', $priceRange->max_price ?? 999999999) }}"
                                min="0"
                                placeholder="999,999,999"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            >
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Available range: {{ number_format($priceRange->min_price ?? 0) }} - {{ number_format($priceRange->max_price ?? 0) }} points
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 bg-gray-50 px-6 py-4 flex gap-3 border-t">
                <button 
                    type="button"
                    onclick="resetFilters()"
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 transition"
                >
                    Reset
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-[#1A1A1D] to-[#374151] text-white font-semibold rounded-lg hover:from-[#374151] hover:to-[#1A1A1D] transition">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modal -->
<script>
    function openFilterModal() {
        document.getElementById('filterModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeFilterModal() {
        document.getElementById('filterModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function resetFilters() {
        const form = document.getElementById('filterForm');
        const inputs = form.querySelectorAll('input[type="radio"]');
        inputs.forEach(input => {
            if (input.value === '') {
                input.checked = true;
            } else {
                input.checked = false;
            }
        });
        form.querySelectorAll('input[type="number"]')[0].value = {{ $priceRange->min_price ?? 0 }};
        form.querySelectorAll('input[type="number"]')[1].value = {{ $priceRange->max_price ?? 999999999 }};
    }

    document.getElementById('filterModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeFilterModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFilterModal();
        }
    });
</script>
@endsection
