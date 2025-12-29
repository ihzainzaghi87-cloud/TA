@extends('customer.layouts.app')

@section('title', 'About Us')

@push('styles')
<style>
    /* Hero Section Styles */
    .hero-gradient {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    }

    /* Mission Card */
    .mission-card {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 2px solid #fcd34d;
        transition: all 0.3s ease;
    }

    .mission-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(252, 211, 77, 0.2);
    }

    /* Value Card */
    .value-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .value-card:hover {
        border-color: #FAD470;
        box-shadow: 0 12px 30px rgba(250, 212, 112, 0.15);
    }

    /* Team Card */
    .team-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .team-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-color: #FAD470;
    }

    /* Stat Counter */
    .stat-card {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #fcd34d;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <section class="hero-gradient py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6">
                        About <span class="text-amber-600">The Paranoia</span>
                    </h1>
                    <p class="text-xl text-gray-700 mb-8 leading-relaxed">
                        We're not just another e-commerce platform. We're your trusted partner in fashion, bringing you the latest trends and timeless classics with unmatched quality and service.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('products') }}"
                           class="inline-flex items-center justify-center px-8 py-4 bg-gray-900 text-white font-semibold rounded-xl hover:bg-gray-800 transition-colors">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            Shop Now
                        </a>
                        <a href="#"
                           class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-900 text-gray-900 font-semibold rounded-xl hover:bg-gray-900 hover:text-white transition-colors">
                            <i class="fas fa-envelope mr-2"></i>
                            Contact Us
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="w-full h-96 bg-gradient-to-br from-amber-200 to-yellow-300 rounded-2xl flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-store text-6xl text-amber-700 mb-4"></i>
                            <p class="text-amber-800 font-semibold text-lg">Quality Fashion Since 2024</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="stat-card rounded-2xl p-8 text-center">
                    <div class="text-4xl font-bold text-amber-700 mb-2">10K+</div>
                    <div class="text-amber-800 font-medium">Happy Customers</div>
                </div>
                <div class="stat-card rounded-2xl p-8 text-center">
                    <div class="text-4xl font-bold text-amber-700 mb-2">500+</div>
                    <div class="text-amber-800 font-medium">Products</div>
                </div>
                <div class="stat-card rounded-2xl p-8 text-center">
                    <div class="text-4xl font-bold text-amber-700 mb-2">50+</div>
                    <div class="text-amber-800 font-medium">Brands</div>
                </div>
                <div class="stat-card rounded-2xl p-8 text-center">
                    <div class="text-4xl font-bold text-amber-700 mb-2">4.8★</div>
                    <div class="text-amber-800 font-medium">Average Rating</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Our Story</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    From a small dream to a growing reality, our journey has been driven by passion for fashion and commitment to quality.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">How We Started</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        The Paranoia began in 2024 with a simple mission: to make quality fashion accessible to everyone. What started as a small online store has grown into a trusted destination for fashion enthusiasts who value style, quality, and affordability.
                    </p>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Our founder, a fashion enthusiast with years of experience in the retail industry, noticed a gap in the market for fashionable yet affordable clothing. This led to the birth of The Paranoia - a platform where style meets substance.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-award text-amber-600"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Quality First</div>
                                <div class="text-sm text-gray-600">Premium materials only</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-shipping-fast text-amber-600"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Fast Delivery</div>
                                <div class="text-sm text-gray-600">Quick shipping</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ asset('ui/store-placeholder.jpg') }}"
                         alt="Our Store"
                         class="w-full h-96 object-cover rounded-2xl shadow-xl"
                         onerror="this.src='https://via.placeholder.com/600x400/fbbf24/1f2937?text=Our+Story'">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Values Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Our Mission & Values</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    We're guided by a clear mission and strong values that shape everything we do.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <!-- Mission Card -->
                <div class="mission-card rounded-2xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-amber-500 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-bullseye text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Our Mission</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        To empower individuals to express their unique style through high-quality, affordable fashion while building a community that celebrates diversity and creativity.
                    </p>
                </div>

                <!-- Vision Card -->
                <div class="mission-card rounded-2xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-amber-500 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-eye text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Our Vision</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        To become the most trusted and innovative fashion destination, setting new standards for customer experience and product quality in the e-commerce industry.
                    </p>
                </div>
            </div>

            <!-- Core Values -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="value-card rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-amber-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Passion</h4>
                    <p class="text-gray-600 text-sm">Love for fashion drives everything we do</p>
                </div>

                <div class="value-card rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-amber-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Integrity</h4>
                    <p class="text-gray-600 text-sm">Honest business practices and transparency</p>
                </div>

                <div class="value-card rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lightbulb text-amber-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Innovation</h4>
                    <p class="text-gray-600 text-sm">Constantly improving and evolving</p>
                </div>

                <div class="value-card rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-amber-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Community</h4>
                    <p class="text-gray-600 text-sm">Building relationships with our customers</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Meet Our Team</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    The talented individuals who work tirelessly to bring you the best shopping experience.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Team Member 1 -->
                <div class="team-card rounded-2xl overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-amber-200 to-yellow-300 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-3 flex items-center justify-center">
                                <span class="text-3xl font-bold text-amber-700">JD</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-semibold text-gray-900 mb-1">John Doe</h4>
                        <p class="text-amber-600 font-medium mb-3">Founder & CEO</p>
                        <p class="text-gray-600 text-sm">Visionary leader with 10+ years in fashion retail</p>
                        <div class="flex space-x-3 mt-4">
                            <a href="#" class="text-gray-400 hover:text-amber-600 transition-colors">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-amber-600 transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="team-card rounded-2xl overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-amber-200 to-yellow-300 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-3 flex items-center justify-center">
                                <span class="text-3xl font-bold text-amber-700">SM</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-semibold text-gray-900 mb-1">Sarah Miller</h4>
                        <p class="text-amber-600 font-medium mb-3">Creative Director</p>
                        <p class="text-gray-600 text-sm">Fashion expert with a keen eye for trends</p>
                        <div class="flex space-x-3 mt-4">
                            <a href="#" class="text-gray-400 hover:text-amber-600 transition-colors">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-amber-600 transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="team-card rounded-2xl overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-amber-200 to-yellow-300 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-3 flex items-center justify-center">
                                <span class="text-3xl font-bold text-amber-700">MJ</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-semibold text-gray-900 mb-1">Michael Johnson</h4>
                        <p class="text-amber-600 font-medium mb-3">Operations Manager</p>
                        <p class="text-gray-600 text-sm">Ensuring smooth operations and customer satisfaction</p>
                        <div class="flex space-x-3 mt-4">
                            <a href="#" class="text-gray-400 hover:text-amber-600 transition-colors">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-amber-600 transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Team Member 4 -->
                <div class="team-card rounded-2xl overflow-hidden">
                    <div class="h-48 bg-gradient-to-br from-amber-200 to-yellow-300 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-3 flex items-center justify-center">
                                <span class="text-3xl font-bold text-amber-700">EC</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-semibold text-gray-900 mb-1">Emily Chen</h4>
                        <p class="text-amber-600 font-medium mb-3">Marketing Lead</p>
                        <p class="text-gray-600 text-sm">Digital marketing strategist and brand builder</p>
                        <div class="flex space-x-3 mt-4">
                            <a href="#" class="text-gray-400 hover:text-amber-600 transition-colors">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-amber-600 transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">
                Ready to Experience The Paranoia Difference?
            </h2>
            <p class="text-xl text-amber-100 mb-8">
                Join thousands of satisfied customers who have discovered their perfect style with us.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products') }}"
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Start Shopping
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white font-semibold rounded-xl hover:bg-white hover:text-amber-600 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>
                    Join Us
                </a>
            </div>
        </div>
    </section>
</div>
@endsection