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
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                                Tracking Information
                            </h3>
                        </div>

                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Tracking Number:</span>
                                <span class="font-mono font-bold text-gray-900 dark:text-white">{{ $order->tracking_number }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Courier:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ strtoupper($order->courier) }} - {{ $order->service }}</span>
                            </div>
                            @if($order->shipped_at)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Shipped Date:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $order->shipped_at->format('d M Y, H:i') }}</span>
                                </div>
                            @endif
                            @if($order->delivered_at)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Delivered Date:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $order->delivered_at->format('d M Y, H:i') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- TRACKING HISTORY FROM API -->
                        @if($trackingData && isset($trackingData['manifest']) && count($trackingData['manifest']) > 0)
                            <div class="mt-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                        Tracking History
                                    </h4>
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">
                                        {{ count($trackingData['manifest']) }} Updates
                                    </span>
                                </div>
                                
                                <div class="relative space-y-4">
                                    @php
                                        // REVERSE array agar oldest to newest
                                        $manifestReversed = ($trackingData['manifest']);
                                        $totalEvents = count($manifestReversed);
                                    @endphp
                                    
                                    @foreach($manifestReversed as $index => $event)
                                        @php
                                            $isLast = $index === ($totalEvents - 1); // Latest event ada di bawah
                                            $isFirst = $index === 0; // Oldest event ada di atas
                                            $isDelivered = isset($event['manifest_description']) && 
                                                        (stripos($event['manifest_description'], 'delivered') !== false || 
                                                        stripos($event['manifest_description'], 'diterima') !== false ||
                                                        stripos($event['manifest_description'], 'selesai') !== false ||
                                                        stripos($event['manifest_description'], 'telah diterima') !== false);
                                        @endphp
                                        
                                        <div class="relative flex items-start gap-4 group">
                                            <!-- Timeline Connector Line -->
                                            @if(!$isLast)
                                                <div class="absolute left-[15px] top-8 bottom-0 w-0.5 
                                                    {{ $isLast ? 'bg-gradient-to-b from-green-400 to-blue-300 dark:from-green-500 dark:to-blue-600' : 'bg-gray-300 dark:bg-gray-600' }}">
                                                </div>
                                            @endif
                                            
                                            <!-- Icon/Checkpoint -->
                                            <div class="relative z-10 flex-shrink-0 mt-1">
                                                @if($isLast && $isDelivered)
                                                    <!-- DELIVERED - Green Checkmark with Animation -->
                                                    <div class="relative">
                                                        <div class="absolute inset-0 bg-green-400 rounded-full animate-ping opacity-30"></div>
                                                        <div class="relative w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-green-600 shadow-lg shadow-green-500/50 flex items-center justify-center ring-4 ring-green-100 dark:ring-green-900/30 transition-transform duration-300 group-hover:scale-110">
                                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @elseif($isLast)
                                                    <!-- 🔵 LATEST UPDATE - Blue Pulsing -->
                                                    <div class="relative">
                                                        <div class="absolute inset-0 bg-blue-500 rounded-full animate-ping opacity-40"></div>
                                                        <div class="relative w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 shadow-lg shadow-blue-500/50 flex items-center justify-center ring-4 ring-blue-100 dark:ring-blue-900/30 transition-transform duration-300 group-hover:scale-110">
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- ⚪ COMPLETED - Checkmark in Circle -->
                                                    <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center transition-all duration-300 group-hover:bg-blue-100 group-hover:border-blue-400 dark:group-hover:bg-blue-900/20 dark:group-hover:border-blue-600">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Event Content Card -->
                                            <div class="flex-1 pb-4 transition-all duration-300">
                                                <div class="bg-white dark:bg-gray-700 rounded-xl p-4 border-2 shadow-sm transition-all duration-300 group-hover:shadow-md
                                                    {{ $isLast && $isDelivered ? 'border-green-300 dark:border-green-600 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10' : 
                                                    ($isLast ? 'border-blue-300 dark:border-blue-600 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10' : 
                                                    'border-gray-200 dark:border-gray-600 group-hover:border-blue-300 dark:group-hover:border-blue-600') }}">
                                                    
                                                    <!-- Status Badge for Latest -->
                                                    @if($isLast)
                                                        <div class="flex items-center gap-2 mb-3">
                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                                                {{ $isDelivered ? 'bg-green-500 text-white shadow-sm shadow-green-500/30' : 'bg-blue-500 text-white shadow-sm shadow-blue-500/30' }}">
                                                                @if($isDelivered)
                                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Delivered
                                                                @else
                                                                    <svg class="w-3 h-3 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Latest Update
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Description -->
                                                    <h5 class="font-bold text-gray-900 dark:text-white text-sm leading-relaxed mb-2">
                                                        {{ $event['manifest_description'] ?? 'Status Update' }}
                                                    </h5>
                                                    
                                                    <!-- Date, Time, Location -->
                                                    <div class="space-y-1.5">
                                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                                                            @if(isset($event['manifest_date']))
                                                                <span class="inline-flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400">
                                                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                    </svg>
                                                                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $event['manifest_date'] }}</span>
                                                                </span>
                                                            @endif
                                                            
                                                            @if(isset($event['manifest_time']))
                                                                <span class="inline-flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400">
                                                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $event['manifest_time'] }}</span>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        
                                                        @if(isset($event['city_name']))
                                                            <div class="inline-flex items-center gap-1.5 text-xs">
                                                                <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                                </svg>
                                                                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $event['city_name'] }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif(isset($trackingData) && (!isset($trackingData['manifest']) || count($trackingData['manifest']) == 0))
                            <!-- No Tracking Data Available -->
                            <div class="mt-6 p-5 bg-gray-50 dark:bg-gray-700/50 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="font-semibold text-gray-700 dark:text-gray-300 mb-1">No tracking history available</h5>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">The courier hasn't provided any tracking updates for this shipment yet.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($order->status == 'Shipped')
                            <!-- Loading State -->
                            <div class="mt-6 p-5 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border-2 border-yellow-200 dark:border-yellow-700">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-1">Loading tracking information...</h5>
                                        <p class="text-sm text-yellow-700 dark:text-yellow-300">Fetching the latest updates from the courier. Please refresh this page in a few moments.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
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
