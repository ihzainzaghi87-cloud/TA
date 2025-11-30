@extends('admin.layouts.app')

@section('title', 'Update Shipping - ' . $order->order_number)

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-800 dark:to-indigo-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Update Shipping Information</h1>
                    <p class="text-blue-100 text-sm">Order #{{ $order->order_number }}</p>
                </div>
                <a href="{{ route('admin.orders.show', $order) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">There were some errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.orders.update-shipping', $order) }}" method="POST" class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Shipping Details</h2>

                <div class="space-y-6">
                    <!-- Order Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Order Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors">
                            <option value="Processing" {{ old('status', $order->status) === 'Processing' ? 'selected' : '' }}>Processing</option>
                            <option value="Shipped" {{ old('status', $order->status) === 'Shipped' ? 'selected' : '' }}>Shipped (Sedang Dikirim)</option>
                            <option value="Delivered" {{ old('status', $order->status) === 'Delivered' ? 'selected' : '' }}>Delivered (Terkirim)</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Select the current status of this order</p>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tracking Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tracking Number (Resi) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="tracking_number" 
                               value="{{ old('tracking_number', $order->tracking_number) }}"
                               required
                               placeholder="e.g., JNE1234567890"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter the tracking/receipt number from the courier</p>
                        @error('tracking_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Important Information</h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>The shipping timestamp will be automatically recorded when you change status to "Shipped"</li>
                                        <li>The delivery timestamp will be automatically recorded when you change status to "Delivered"</li>
                                        <li>Customer will be able to track their order using the tracking number</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Shipping Information
                        </button>
                        <a href="{{ route('admin.orders.show', $order) }}" 
                           class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Current Status -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Current Status</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Order Status</p>
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
                    @if($order->tracking_number)
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Current Tracking</p>
                            <p class="font-mono text-sm text-gray-900 dark:text-white">{{ $order->tracking_number }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Shipping Info</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Courier</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ strtoupper($order->courier) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Service</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $order->service }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Weight</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $order->weight }}g</span>
                    </div>
                </div>
            </div>

            <!-- Destination -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Destination</h3>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <p class="font-medium text-gray-900 dark:text-white">{{ $order->shipping_recipient_name }}</p>
                    <p>{{ $order->shipping_phone }}</p>
                    <p class="mt-2">{{ $order->shippingAddress->city_name }}, {{ $order->shippingAddress->province_name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
