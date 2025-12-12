@props(['show' => false])

<div x-data="{ loadingOverlay: {{ $show ? 'true' : 'false' }} }" 
     x-show="loadingOverlay" 
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center"
     style="backdrop-filter: blur(2px);">
    
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/30"></div>
    
    <!-- Loading Content -->
    <div class="relative bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-4 z-10">
        <div class="flex flex-col items-center space-y-4">
            <!-- Spinner -->
            <div class="relative">
                <div class="w-12 h-12 rounded-full border-4 border-emerald-200"></div>
                <div class="w-12 h-12 rounded-full border-4 border-emerald-600 border-t-transparent animate-spin absolute top-0 left-0"></div>
            </div>
            
            <!-- Loading Text -->
            <div class="text-center">
                <h3 class="text-lg font-semibold text-slate-900 mb-1">Processing...</h3>
                <p class="text-sm text-slate-600">Please wait while we complete your request</p>
            </div>
            
            <!-- Progress Dots -->
            <div class="flex space-x-2">
                <div class="w-2 h-2 bg-emerald-600 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                <div class="w-2 h-2 bg-emerald-600 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                <div class="w-2 h-2 bg-emerald-600 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
            </div>
        </div>
    </div>
    
    <!-- Prevent interaction with underlying content -->
    <div class="absolute inset-0 cursor-wait"></div>
</div>

@once
@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush
@endonce
