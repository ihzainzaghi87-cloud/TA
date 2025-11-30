@extends('customer.layouts.app')

@section('title', 'Pembayaran - The Paranoia')

@push('styles')
<style>
    .payment-gradient {
        background: linear-gradient(90deg, #9333ea, #ec4899);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Payment Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 payment-gradient rounded-full mb-4 shadow-2xl">
                <i class="fas fa-credit-card text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-transparent bg-clip-text payment-gradient mb-3">
                Pembayaran
            </h1>
            <p class="text-gray-600 text-lg">Order #{{ $order->order_number }}</p>
        </div>

        {{-- Order Summary Card --}}
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Ringkasan Pesanan</h2>
            
            <div class="space-y-3 mb-6">
                <div class="flex justify-between text-gray-700">
                    <span>Subtotal</span>
                    <span class="font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-700">
                    <span>Ongkir</span>
                    <span class="font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @if($order->total_points_used > 0)
                    <div class="flex justify-between text-red-600">
                        <span><i class="fas fa-star mr-1"></i>Poin Digunakan</span>
                        <span class="font-semibold">{{ number_format($order->total_points_used, 0, ',', '.') }} poin</span>
                    </div>
                @endif
                <div class="border-t-2 border-gray-300 pt-3">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Total Bayar</span>
                        <span class="text-3xl font-bold text-transparent bg-clip-text payment-gradient">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Midtrans Payment Button --}}
            <button id="pay-button" 
                    class="w-full py-4 payment-gradient text-white rounded-full font-bold text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 transition duration-300">
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
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4">Metode Pembayaran yang Tersedia:</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center text-sm">
                <div class="p-3 bg-gray-50 rounded-xl">
                    <i class="fas fa-credit-card text-purple-600 text-2xl mb-2"></i>
                    <p class="font-semibold">Credit Card</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl">
                    <i class="fas fa-university text-purple-600 text-2xl mb-2"></i>
                    <p class="font-semibold">Bank Transfer</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl">
                    <i class="fas fa-wallet text-purple-600 text-2xl mb-2"></i>
                    <p class="font-semibold">E-Wallet</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl">
                    <i class="fas fa-store text-purple-600 text-2xl mb-2"></i>
                    <p class="font-semibold">Indomaret</p>
                </div>
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
                    window.location.href = "{{ route('orders.show', $order->id) }}";
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
