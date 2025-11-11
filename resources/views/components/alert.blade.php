@props(['type' => 'info', 'message'])

@php
    $classes = [
        'success' => 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200',
        'error' => 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200',
        'warning' => 'bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 text-yellow-800 dark:text-yellow-200',
        'info' => 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 text-blue-800 dark:text-blue-200',
    ];

    $iconColors = [
        'success' => 'text-green-500',
        'error' => 'text-red-500',
        'warning' => 'text-yellow-500',
        'info' => 'text-blue-500',
    ];

    $icons = [
        'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'error' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
        'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z',
        'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    ];
@endphp

<div class="mb-6 p-4 rounded-xl {{ $classes[$type] }} shadow-lg" 
     x-data="{ show: true }" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <div class="w-8 h-8 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5 {{ $iconColors[$type] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$type] }}"></path>
                </svg>
            </div>
        </div>
        <div class="ml-3 flex-1">
            <div class="text-sm font-medium">
                {{ $message }}
            </div>
        </div>
        <div class="ml-4 flex-shrink-0">
            <button @click="show = false" 
                    class="inline-flex text-current opacity-70 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-transparent focus:ring-current rounded-full p-1 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>