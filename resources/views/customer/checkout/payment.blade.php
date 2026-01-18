@extends('customer.layouts.app')

@section('title', 'Payment - The Paranoia')

@push('styles')
<style>
    /* Hero Section - Dark Mode */
    .payment-hero-bg { 
        background-color: #0c0c0c; 
    }

    /* Cards - Clean White with Thin Borders */
    .payment-card { 
        background: #fff; 
        border: 1px solid #e5e7eb;
        border-radius: 1.5rem; /* Rounded 24px */
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    /* Primary Button - Solid Black */
    .payment-primary-btn { 
        background-color: #1A1A1D; 
        color: #ffffff; 
        border: 1px solid #1A1A1D;
        transition: all 0.3s ease;
    }
    .payment-primary-btn:hover { 
        background-color: #333333; 
        border-color: #333333;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    /* Method Cards - Minimalist */
    .method-card {
        background-color: #F9FAFB;
        border: 1px solid #e5e7eb;
        color: #1A1A1D;
        transition: all 0.2s ease;
    }
    .method-card:hover {
        background-color: #ffffff;
        border-color: #1A1A1D; /* Hover border turns black */
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="payment-hero-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-6 shadow-lg">
            <i class="fas fa-credit-card text-[#1A1A1D] text-3xl"></i>
        </div>
        <h1 class="font-bebas text-5xl md:text-6xl text-white mb-2 tracking-wide">PAYMENT</h1>
        <p class="text-gray-400 text-lg font-medium">Order #{{ $order->order_number }}</p>
    </div>
</section>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 pb-20 relative z-10">
    
    {{-- Order Summary Card --}}
    <div class="payment-card p-8 mb-8">
        <div class="text-center pb-6 border-b border-gray-100">
            <h2 class="font-bebas text-3xl text-[#1A1A1D]">ORDER SUMMARY</h2>
            <p class="text-xs text-gray-400 uppercase tracking-widest mt-1">Please review before paying</p>
        </div>
        
        <div class="space-y-4 my-8">
            <div class="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span class="font-bold text-[#1A1A1D]">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            
            <div class="flex justify-between text-gray-600">
                <span class="flex items-center gap-2">
                    <i class="fas fa-truck text-[#1A1A1D]"></i> Shipping
                </span>
                <span class="font-bold text-[#1A1A1D]">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>

            @if($order->total_points_used > 0)
                <div class="flex justify-between text-red-600 bg-red-50 p-3 rounded-lg border border-red-100">
                    <span class="flex items-center gap-2 font-medium">
                        <i class="fas fa-coins"></i> Points Used
                    </span>
                    <span class="font-bold">- {{ number_format($order->total_points_used, 0, ',', '.') }} pts</span>
                </div>
            @endif

            <div class="border-t-2 border-[#1A1A1D] pt-6 mt-4">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-900">Total Amount</span>
                    <span class="text-4xl font-bebas text-[#1A1A1D]">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Midtrans Payment Button --}}
        <button id="pay-button" 
                class="w-full py-4 payment-primary-btn rounded-xl font-bold text-lg flex items-center justify-center gap-3">
            <i class="fas fa-lock"></i>
            PAY NOW
        </button>

        <div class="mt-6 text-center">
            <p class="text-xs text-gray-400 flex items-center justify-center gap-2">
                <i class="fas fa-shield-alt text-green-600"></i>
                Secured by Midtrans Payment Gateway
            </p>
        </div>
    </div>

    {{-- Payment Methods Info --}}
    <div class="payment-card p-8">
        <h3 class="font-bebas text-2xl text-[#1A1A1D] mb-6 text-center">ACCEPTED PAYMENT METHODS</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center text-sm">
            <div class="method-card p-5 rounded-2xl">
                <div class="w-10 h-10 mx-auto bg-white rounded-full flex items-center justify-center mb-3 shadow-sm border border-gray-100">
                    <i class="fas fa-credit-card text-[#1A1A1D] text-lg"></i>
                </div>
                <p class="font-bold text-[#1A1A1D]">Credit Card</p>
                <p class="text-[10px] text-gray-400">Visa / Master</p>
            </div>

            <div class="method-card p-5 rounded-2xl">
                <div class="w-10 h-10 mx-auto bg-white rounded-full flex items-center justify-center mb-3 shadow-sm border border-gray-100">
                    <i class="fas fa-university text-[#1A1A1D] text-lg"></i>
                </div>
                <p class="font-bold text-[#1A1A1D]">Bank Transfer</p>
                <p class="text-[10px] text-gray-400">VA / Manual</p>
            </div>

            <div class="method-card p-5 rounded-2xl">
                <div class="w-10 h-10 mx-auto bg-white rounded-full flex items-center justify-center mb-3 shadow-sm border border-gray-100">
                    <i class="fas fa-wallet text-[#1A1A1D] text-lg"></i>
                </div>
                <p class="font-bold text-[#1A1A1D]">E-Wallet</p>
                <p class="text-[10px] text-gray-400">QRIS / GoPay</p>
            </div>

            <div class="method-card p-5 rounded-2xl">
                <div class="w-10 h-10 mx-auto bg-white rounded-full flex items-center justify-center mb-3 shadow-sm border border-gray-100">
                    <i class="fas fa-store text-[#1A1A1D] text-lg"></i>
                </div>
                <p class="font-bold text-[#1A1A1D]">Retail</p>
                <p class="text-[10px] text-gray-400">Indomaret / Alfa</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Midtrans Snap JS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const payButton = document.getElementById('pay-button');
        
        payButton.addEventListener('click', function () {
            // Trigger Midtrans Snap popup
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    // Redirect to Success Page using Order Number
                    window.location.href = "{{ route('checkout.success', $order->order_number) }}";
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    alert('Waiting for your payment!');
                    // Redirect to Order Detail
                    window.location.href = "{{ route('customer.order-detail', $order->id) }}";
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Payment failed! Please try again.');
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    // Optional: Show a toast or small alert
                }
            });
        });
    });
</script>
@endpush