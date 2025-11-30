@extends('admin.layouts.app')

@section('title', 'Order Detail - ' . $order->order_number)

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-800 dark:to-indigo-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Order #{{ $order->order_number }}</h1>
                    <p class="text-blue-100 text-sm">Created at {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Orders
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Alert -->
    @if (session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    @if (session('error'))
        <x-alert type="error" :message="session('error')" />
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status Card -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Order Status</h2>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Order Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($order->status == 'Pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'Processing') bg-blue-100 text-blue-800
                            @elseif($order->status == 'Shipped') bg-purple-100 text-purple-800
                            @elseif($order->status == 'Delivered') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $order->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Payment Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($order->payment_status == 'Paid') bg-green-100 text-green-800
                            @elseif($order->payment_status == 'Pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $order->payment_status }}
                        </span>
                    </div>
                </div>

                <!-- Quick Action Buttons -->
                @if($order->payment_status === 'Paid' && $order->status !== 'Cancelled' && $order->status !== 'Delivered')
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.orders.edit-shipping', $order) }}" 
                           class="inline-flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                            Update Shipping Information
                        </a>
                    </div>
                @endif

                <!-- Tracking Information -->
                @if($order->tracking_number)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-3">Tracking Information</h3>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Tracking Number:</span>
                                <span class="font-mono font-bold text-gray-900 dark:text-white">{{ $order->tracking_number }}</span>
                            </div>
                            @if($order->shipped_at)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Shipped Date:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $order->shipped_at->format('d M Y, H:i') }}</span>
                                </div>
                            @endif
                            @if($order->delivered_at)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Delivered Date:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $order->delivered_at->format('d M Y, H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Items -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Order Items ({{ $order->orderItems->count() }})</h2>
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex gap-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                            @if($item->variation->product->images->first())
                                <img src="{{ asset('storage/products/' . $item->variation->product->images->first()->image) }}" 
                                     class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 dark:text-white">{{ $item->product_name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->variant_details }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Qty: {{ $item->quantity }}</p>
                                @if($item->point_subtotal > 0)
                                    <p class="text-sm text-amber-600">
                                        <svg class="w-3 h-3 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        {{ number_format($item->point_subtotal) }} points
                                    </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Customer</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Name</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Email</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $order->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Shipping Address</h2>
                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <p class="font-medium text-gray-900 dark:text-white">{{ $order->shipping_recipient_name }}</p>
                    <p>{{ $order->shipping_phone }}</p>
                    <p>{{ $order->shippingAddress->address }}</p>
                    <p>{{ $order->shippingAddress->city_name }}, {{ $order->shippingAddress->province_name }} {{ $order->shippingAddress->postal_code }}</p>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 text-sm">
                    <p class="text-gray-600 dark:text-gray-400">Courier: <span class="font-medium text-gray-900 dark:text-white">{{ strtoupper($order->courier) }} - {{ $order->service }}</span></p>
                    <p class="text-gray-600 dark:text-gray-400">Weight: <span class="font-medium text-gray-900 dark:text-white">{{ $order->weight }} gram</span></p>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Summary</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                        <span class="text-gray-900 dark:text-white">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Shipping</span>
                        <span class="text-gray-900 dark:text-white">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @if($order->total_points_used > 0)
                        <div class="flex justify-between text-amber-600">
                            <span>Points Used</span>
                            <span class="font-medium">{{ number_format($order->total_points_used) }} points</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-200 dark:border-gray-700">
                        <span class="text-gray-900 dark:text-white">Total</span>
                        <span class="text-gray-900 dark:text-white">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    @if($order->points_earned > 0)
                        <div class="flex justify-between text-green-600 text-xs pt-2">
                            <span>Points Earned</span>
                            <span class="font-medium">+{{ number_format($order->points_earned) }} points</span>
                        </div>
                    @endif
                </div>
            </div>

            @if($order->notes)
            <!-- Order Notes -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Notes</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
