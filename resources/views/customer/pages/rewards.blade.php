@extends('customer.layouts.app')

@section('title', 'Rewards & Benefits')

@push('styles')
<style>
    /* Hero Section */
    .hero-gradient {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    }

    /* Reward Cards */
    .reward-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .reward-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-color: #FAD470;
    }

    /* Points Card */
    .points-card {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #fcd34d;
    }

    /* Tier Card */
    .tier-card {
        background: linear-gradient(135deg, #fff 0%, #fef3c7 100%);
        border: 2px solid #fcd34d;
        transition: all 0.3s ease;
    }

    .tier-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px rgba(252, 211, 77, 0.2);
    }

    .tier-card.active {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        border-color: #d97706;
    }

    /* Benefit Item */
    .benefit-item {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .benefit-item:hover {
        border-color: #FAD470;
        background: #fffbeb;
    }

    /* Progress Bar */
    .progress-bar {
        background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 100%);
    }

    /* Promo Card */
    .promo-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .promo-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    }

    /* Stats Counter */
    .stat-circle {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 3px solid #fcd34d;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="hero-gradient py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6">
                    Rewards & Benefits
                </h1>
                <p class="text-xl text-amber-100 max-w-3xl mx-auto mb-8">
                    Earn points, unlock exclusive perks, and enjoy special member benefits with The Paranoia Rewards Program.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                            <i class="fas fa-gift mr-2"></i>
                            Join Rewards Program
                        </a>
                    @else
                        <a href="{{ route('customer.points') }}"
                           class="inline-flex items-center justify-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                            <i class="fas fa-coins mr-2"></i>
                            View My Points
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Rewards Stats -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="stat-circle w-32 h-32 mx-auto rounded-full flex flex-col items-center justify-center">
                    <div class="text-2xl font-bold text-amber-700">100 pts</div>
                    <div class="text-xs text-amber-800 font-medium text-center">per purchase</div>
                </div>
                <div class="stat-circle w-32 h-32 mx-auto rounded-full flex flex-col items-center justify-center">
                    <div class="text-2xl font-bold text-amber-700">2X pts</div>
                    <div class="text-xs text-amber-800 font-medium text-center">member days</div>
                </div>
                <div class="stat-circle w-32 h-32 mx-auto rounded-full flex flex-col items-center justify-center">
                    <div class="text-2xl font-bold text-amber-700">25% OFF</div>
                    <div class="text-xs text-amber-800 font-medium text-center">birthday</div>
                </div>
                <div class="stat-circle w-32 h-32 mx-auto rounded-full flex flex-col items-center justify-center">
                    <div class="text-2xl font-bold text-amber-700">Free Ship</div>
                    <div class="text-xs text-amber-800 font-medium text-center">VIP tier</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Member Tiers -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Membership Tiers</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Unlock more benefits as you climb up the membership ladder.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Bronze Tier -->
                <div class="tier-card rounded-2xl p-8">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-amber-700 to-amber-900 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-medal text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Bronze</h3>
                        <p class="text-gray-600">0 - 999 Points</p>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check text-amber-600 mr-3"></i>
                            <span class="text-gray-700">1 point per $1 spent</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-amber-600 mr-3"></i>
                            <span class="text-gray-700">Birthday discount</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-amber-600 mr-3"></i>
                            <span class="text-gray-700">Early access to sales</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-amber-600 mr-3"></i>
                            <span class="text-gray-700">Exclusive promos</span>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-amber-600 font-semibold">Join FREE</div>
                    </div>
                </div>

                <!-- Silver Tier -->
                <div class="tier-card active rounded-2xl p-8">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-300 to-gray-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-medal text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Silver</h3>
                        <p class="text-amber-100">1,000 - 4,999 Points</p>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check text-white mr-3"></i>
                            <span class="text-amber-100">All Bronze benefits</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-white mr-3"></i>
                            <span class="text-amber-100">1.5x points multiplier</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-white mr-3"></i>
                            <span class="text-amber-100">15% birthday discount</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-white mr-3"></i>
                            <span class="text-amber-100">Free shipping over $30</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-white mr-3"></i>
                            <span class="text-amber-100">Member-only events</span>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-white font-semibold">Most Popular</div>
                    </div>
                </div>

                <!-- Gold Tier -->
                <div class="tier-card rounded-2xl p-8">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-crown text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Gold</h3>
                        <p class="text-gray-600">5,000+ Points</p>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check text-amber-600 mr-3"></i>
                            <span class="text-gray-700">All Silver benefits</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-amber-600 mr-3"></i>
                            <span class="text-gray-700">2x points multiplier</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-amber-600 mr-3"></i>
                            <span class="text-gray-700">25% birthday discount</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-amber-600 mr-3"></i>
                            <span class="text-gray-700">Free shipping always</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-amber-600 mr-3"></i>
                            <span class="text-gray-700">Personal shopper service</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-amber-600 mr-3"></i>
                            <span class="text-gray-700">Priority customer support</span>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-amber-600 font-semibold">VIP Status</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Start earning rewards today in just a few simple steps.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-amber-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                        <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-xl">1</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Join & Shop</h3>
                    <p class="text-gray-600">
                        Create your free account and start shopping. You'll automatically earn 1 point for every dollar spent.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-amber-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                        <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-xl">2</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Earn Points</h3>
                    <p class="text-gray-600">
                        Earn points on every purchase, review, and referral. Get bonus points during special promotions.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-amber-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                        <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-xl">3</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Redeem Rewards</h3>
                    <p class="text-gray-600">
                        Use your points to get discounts, free products, and exclusive experiences. The more points, the bigger the rewards!
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Current Promotions -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Current Promotions</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Don't miss out on these special member-exclusive offers.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Promo 1 -->
                <div class="promo-card rounded-2xl overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                        <div class="text-center text-white">
                            <i class="fas fa-percentage text-5xl mb-3"></i>
                            <h3 class="text-2xl font-bold">20% OFF</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Double Points Weekend</h4>
                        <p class="text-gray-600 mb-4">Earn 2x points on all purchases this weekend only!</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Valid until Dec 31</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Limited Time</span>
                        </div>
                    </div>
                </div>

                <!-- Promo 2 -->
                <div class="promo-card rounded-2xl overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                        <div class="text-center text-white">
                            <i class="fas fa-gift text-5xl mb-3"></i>
                            <h3 class="text-2xl font-bold">FREE GIFT</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Mystery Gift</h4>
                        <p class="text-gray-600 mb-4">Spend $100+ and get a free mystery gift with your order!</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Valid until Dec 25</span>
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">Member Exclusive</span>
                        </div>
                    </div>
                </div>

                <!-- Promo 3 -->
                <div class="promo-card rounded-2xl overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
                        <div class="text-center text-white">
                            <i class="fas fa-truck text-5xl mb-3"></i>
                            <h3 class="text-2xl font-bold">FREE SHIP</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Free Shipping</h4>
                        <p class="text-gray-600 mb-4">Free shipping on all orders for Silver & Gold members!</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Ongoing</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Always Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">
                Ready to Start Earning Rewards?
            </h2>
            <p class="text-xl text-amber-100 mb-8">
                Join thousands of members who are already enjoying exclusive benefits and savings.
            </p>
            @guest
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="fas fa-rocket mr-2"></i>
                    Join Now - It's Free!
                </a>
            @else
                <a href="{{ route('products') }}"
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Start Earning Points
                </a>
            @endguest
        </div>
    </section>
</div>
@endsection