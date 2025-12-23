@extends('customer.layouts.app')

@section('title', 'Pembayaran - The Paranoia')

@push('styles')
<style>
    .payment-hero-bg { background-color: #FAD470; }
    .payment-primary-btn { background-color: #000; color: #FAD471; }
    .payment-primary-btn:hover { background-color: #333; }
    .payment-card { 
        background: #fff; 
        border: 2px solid #FAD470;
        border-radius: 1.5rem;
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="payment-hero-bg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-black rounded-full mb-4 shadow-2xl">
            <i class="fas fa-credit-card text-[#FAD470] text-3xl"></i>
        </div>
        <h1 class="font-bebas text-5xl md:text-6xl text-black mb-3">PEMBAYARAN</h1>
        <p class="text-black/70 text-lg">Order #{{ $order->order_number }}</p>
    </div>
</section>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 pb-20 relative z-10">
    {{-- Order Summary Card --}}
    <div class="payment-card shadow-xl p-8 mb-6">
        <div class="text-center pb-6 border-b-2 border-[#FAD470]">
            <h2 class="font-bebas text-3xl text-black">RINGKASAN PESANAN</h2>
        </div>
        
        <div class="space-y-4 my-6">
            <div class="flex justify-between text-gray-700">
                <span>Subtotal</span>
                <span class="font-bold text-black">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-700">
                <span class="flex items-center gap-2">
                    <i class="fas fa-truck text-[#FAD470]"></i> Ongkir
                </span>
                <span class="font-bold text-black">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            @if($order->total_points_used > 0)
                <div class="flex justify-between text-red-600">
                    <span><i class="fas fa-star mr-1"></i>Poin Digunakan</span>
                    <span class="font-bold">{{ number_format($order->total_points_used, 0, ',', '.') }} poin</span>
                </div>
            @endif
            <div class="border-t-2 border-[#FAD470] pt-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-900">Total Bayar</span>
                    <span class="text-4xl font-bebas text-black">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Midtrans Payment Button --}}
        <button id="pay-button" 
                class="w-full py-4 payment-primary-btn rounded-full font-bold text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 transition duration-300">
            <i class="fas fa-credit-card mr-2"></i>
            Bayar Sekarang
        </button>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500 flex items-center justify-center gap-2">
                <i class="fas fa-shield-alt text-green-500"></i>
                Pembayaran diamankan dengan Midtrans
            </p>
        </div>
    </div>

    {{-- Payment Methods Info --}}
    <div class="payment-card shadow-xl p-6">
        <h3 class="font-bebas text-2xl text-black mb-4 text-center">METODE PEMBAYARAN</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center text-sm">
            <div class="p-4 bg-[#FAD470]/20 rounded-2xl border-2 border-[#FAD470]/50 hover:border-[#FAD470] transition">
                <i class="fas fa-credit-card text-black text-2xl mb-2"></i>
                <p class="font-bold text-black">Credit Card</p>
            </div>
            <div class="p-4 bg-[#FAD470]/20 rounded-2xl border-2 border-[#FAD470]/50 hover:border-[#FAD470] transition">
                <i class="fas fa-university text-black text-2xl mb-2"></i>
                <p class="font-bold text-black">Bank Transfer</p>
            </div>
            <div class="p-4 bg-[#FAD470]/20 rounded-2xl border-2 border-[#FAD470]/50 hover:border-[#FAD470] transition">
                <i class="fas fa-wallet text-black text-2xl mb-2"></i>
                <p class="font-bold text-black">E-Wallet</p>
            </div>
            <div class="p-4 bg-[#FAD470]/20 rounded-2xl border-2 border-[#FAD470]/50 hover:border-[#FAD470] transition">
                <i class="fas fa-store text-black text-2xl mb-2"></i>
                <p class="font-bold text-black">Indomaret</p>
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
                    // ✅ PERBAIKAN: Kirim order_number, bukan id
                    window.location.href = "{{ route('checkout.success', $order->order_number) }}";
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    alert('Menunggu pembayaran Anda!');
                    // ✅ Tetap pakai id untuk detail order (sesuai route yang ada)
                    window.location.href = "{{ route('customer.order-detail', $order->id) }}";
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Pembayaran gagal! Silakan coba lagi.');
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    alert('Anda menutup popup pembayaran. Silakan selesaikan pembayaran untuk melanjutkan.');
                }
            });
        });
    });
</script>
@endpush
