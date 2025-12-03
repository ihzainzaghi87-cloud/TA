@extends('admin.layouts.app')

@section('title', 'User Point Detail')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="relative bg-gradient-to-r from-yellow-600 to-amber-600 dark:from-yellow-800 dark:to-amber-800 overflow-hidden shadow-lg rounded-xl">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-1">User Point Detail</h1>
                    <p class="text-yellow-100 text-sm">{{ $userPoint->user->name }}</p>
                </div>
                <a href="{{ route('admin.user-points.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 rounded-lg text-white hover:bg-opacity-30 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    <!-- User Info Card -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <div class="flex items-start space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $userPoint->user->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $userPoint->user->email }}</p>
                    
                    <div class="flex items-center gap-6">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Points</p>
                            <div class="inline-flex items-center px-4 py-2 rounded-full text-lg font-bold bg-gradient-to-r from-yellow-400 to-amber-500 text-white">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                {{ number_format($userPoint->total_points) }} pts
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Last Updated</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $userPoint->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Transaction History</h3>
                <span class="text-xs text-gray-600 dark:text-gray-400">Total {{ $transactions->total() }} transactions</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/40">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Points</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($transactions as $transaction)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                        <td class="px-4 py-3">
                            <div class="text-xs text-gray-900 dark:text-white font-medium">{{ $transaction->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @if($transaction->type === 'earned')
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Earned
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                                Redeemed
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $transaction->description }}</div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-sm font-bold {{ $transaction->points > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ number_format($transaction->balance_after) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No transactions yet</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This user hasn't made any point transactions.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900/40 border-t border-gray-200 dark:border-gray-700">
            {{ $transactions->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection
