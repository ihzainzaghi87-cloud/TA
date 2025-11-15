{{-- Customer Footer Component --}}
<footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
    {{-- Main Footer Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Brand Section --}}
            <div class="space-y-4">
                <h3 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                    The Paranoia
                </h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Your trusted e-commerce destination for quality products and exceptional service. Shop with confidence at The Paranoia.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-purple-400 transition duration-300 transform hover:scale-110">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-purple-400 transition duration-300 transform hover:scale-110">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-purple-400 transition duration-300 transform hover:scale-110">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-purple-400 transition duration-300 transform hover:scale-110">
                        <i class="fab fa-linkedin text-xl"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-lg font-semibold mb-4 text-white">Quick Links</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-purple-400 text-sm transition duration-300">
                            <i class="fas fa-chevron-right text-xs mr-2"></i> Home
                        </a>
                    </li>
                    <li>
                        <a href="#about" class="text-gray-400 hover:text-purple-400 text-sm transition duration-300">
                            <i class="fas fa-chevron-right text-xs mr-2"></i> About Us
                        </a>
                    </li>
                    <li>
                        <a href="#products" class="text-gray-400 hover:text-purple-400 text-sm transition duration-300">
                            <i class="fas fa-chevron-right text-xs mr-2"></i> Products
                        </a>
                    </li>
                    <li>
                        <a href="#blog" class="text-gray-400 hover:text-purple-400 text-sm transition duration-300">
                            <i class="fas fa-chevron-right text-xs mr-2"></i> Blog
                        </a>
                    </li>
                    <li>
                        <a href="#contact" class="text-gray-400 hover:text-purple-400 text-sm transition duration-300">
                            <i class="fas fa-chevron-right text-xs mr-2"></i> Contact
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Customer Service --}}
            <div>
                <h4 class="text-lg font-semibold mb-4 text-white">Customer Service</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-gray-400 hover:text-purple-400 text-sm transition duration-300">
                            <i class="fas fa-chevron-right text-xs mr-2"></i> FAQ
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-purple-400 text-sm transition duration-300">
                            <i class="fas fa-chevron-right text-xs mr-2"></i> Shipping Info
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-purple-400 text-sm transition duration-300">
                            <i class="fas fa-chevron-right text-xs mr-2"></i> Return Policy
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-purple-400 text-sm transition duration-300">
                            <i class="fas fa-chevron-right text-xs mr-2"></i> Privacy Policy
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-purple-400 text-sm transition duration-300">
                            <i class="fas fa-chevron-right text-xs mr-2"></i> Terms & Conditions
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Contact Info --}}
            <div>
                <h4 class="text-lg font-semibold mb-4 text-white">Contact Us</h4>
                <ul class="space-y-3">
                    <li class="flex items-start text-gray-400 text-sm">
                        <i class="fas fa-map-marker-alt mt-1 mr-3 text-purple-400"></i>
                        <span>123 E-Commerce St, Digital City, 12345</span>
                    </li>
                    <li class="flex items-start text-gray-400 text-sm">
                        <i class="fas fa-phone mt-1 mr-3 text-purple-400"></i>
                        <span>+1 (234) 567-890</span>
                    </li>
                    <li class="flex items-start text-gray-400 text-sm">
                        <i class="fas fa-envelope mt-1 mr-3 text-purple-400"></i>
                        <span>info@theparanoia.com</span>
                    </li>
                    <li class="flex items-start text-gray-400 text-sm">
                        <i class="fas fa-clock mt-1 mr-3 text-purple-400"></i>
                        <span>Mon - Fri: 9AM - 6PM</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Payment Methods & Trust Badges --}}
    <div class="border-t border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-6">
                    <span class="text-gray-400 text-sm">We Accept:</span>
                    <div class="flex space-x-3">
                        <i class="fab fa-cc-visa text-2xl text-gray-400 hover:text-white transition duration-300"></i>
                        <i class="fab fa-cc-mastercard text-2xl text-gray-400 hover:text-white transition duration-300"></i>
                        <i class="fab fa-cc-paypal text-2xl text-gray-400 hover:text-white transition duration-300"></i>
                        <i class="fab fa-cc-amex text-2xl text-gray-400 hover:text-white transition duration-300"></i>
                    </div>
                </div>
                <div class="flex items-center space-x-4 text-sm text-gray-400">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt mr-2 text-purple-400"></i>
                        <span>Secure Payment</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-lock mr-2 text-purple-400"></i>
                        <span>SSL Encrypted</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright --}}
    <div class="border-t border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-2 md:space-y-0">
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} The Paranoia. All rights reserved.
                </p>
                <p class="text-gray-400 text-sm">
                    Made with <i class="fas fa-heart text-red-500 mx-1"></i> for our customers
                </p>
            </div>
        </div>
    </div>
</footer>
