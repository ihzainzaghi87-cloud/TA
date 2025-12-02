@extends('admin.layouts.app')

@section('title', 'Transaction Detail')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-800 dark:to-cyan-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">Transaction Detail</h1>
                    <p class="text-blue-100 text-sm">Transaction ID: #{{ $transaction->id }}</p>
                </div>
                <a href="{{ route('admin.point-transactions.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Transaction Info Card -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Transaction Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Info -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">User</label>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $transaction->user->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->user->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Type -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Type</label>
                    @if($transaction->type === 'earned')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Points Earned
                    </span>
                    @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                        Points Redeemed
                    </span>
                    @endif
                </div>

                <!-- Points -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Points</label>
                    <div class="text-2xl font-bold {{ $transaction->points > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                    </div>
                </div>

                <!-- Balance After -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Balance After</label>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($transaction->balance_after) }} pts
                    </div>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Description</label>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $transaction->description }}</p>
                </div>

                <!-- Transaction Date -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Transaction Date</label>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $transaction->created_at->format('d F Y, H:i:s') }}</p>
                </div>

                <!-- Related Entity -->
                @if($transaction->transactionable_type && $transaction->transactionable_id)
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Related To</label>
                    <p class="text-sm text-gray-900 dark:text-white">
                        {{ class_basename($transaction->transactionable_type) }} #{{ $transaction->transactionable_id }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex gap-3">
        <a href="{{ route('admin.point-transactions.statistics', $transaction->user_id) }}" 
           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 border border-transparent rounded-lg text-sm font-medium text-white hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            View User Statistics
        </a>

        <a href="{{ route('admin.user-points.show', $transaction->user_id) }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            View User Points
        </a>
    </div>
</div>
@endsection
