{{-- Global Loading Overlay Component --}}
{{-- Usage: Include this once in your main layout, then control it globally from anywhere --}}
{{-- 
    Control methods:
    - window.showLoading(message, title)
    - window.hideLoading()
    
    Example:
    <button @click="window.showLoading('Saving tour...', 'Please Wait')">Save</button>
--}}

<div x-data="{
    show: false,
    title: 'Processing...',
    message: 'Please wait while we complete your request',
    
    init() {
        // Ensure it starts hidden
        this.show = false;
        
        // Global methods to control loading state
        window.showLoading = (message = 'Please wait while we complete your request', title = 'Processing...') => {
            this.message = message;
            this.title = title;
            // Small delay to ensure Alpine is ready
            setTimeout(() => {
                this.show = true;
            }, 10);
        };
        
        window.hideLoading = () => {
            this.show = false;
        };
        
        // Auto-hide on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.show) {
                this.show = false;
            }
        });
    }
}" 
x-show="show" 
x-cloak
x-transition:enter="transition ease-out duration-200"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in duration-150"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
class="fixed inset-0 z-50 flex items-center justify-center global-loading-overlay"
style="backdrop-filter: blur(2px);">

    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"></div>
    
    <!-- Loading Content -->
    <div class="relative bg-white rounded-xl shadow-2xl p-8 max-w-sm mx-4 z-10"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100">
        
        <div class="flex flex-col items-center space-y-4">
            <!-- Animated Spinner -->
            <div class="relative">
                <div class="w-16 h-16 rounded-full border-4 border-emerald-100"></div>
                <div class="w-16 h-16 rounded-full border-4 border-emerald-600 border-t-transparent animate-spin absolute top-0 left-0"></div>
                <!-- Inner spinner -->
                <div class="w-8 h-8 rounded-full border-2 border-emerald-400 border-b-transparent animate-spin absolute top-4 left-4"></div>
            </div>
            
            <!-- Dynamic Text -->
            <div class="text-center">
                <h3 class="text-lg font-semibold text-slate-900 mb-1" x-text="title"></h3>
                <p class="text-sm text-slate-600" x-text="message"></p>
            </div>
            
            <!-- Progress Animation -->
            <div class="flex items-center space-x-1">
                <div class="w-1 h-8 bg-emerald-600 rounded-full animate-pulse" style="animation-delay: 0ms;"></div>
                <div class="w-1 h-12 bg-emerald-500 rounded-full animate-pulse" style="animation-delay: 100ms;"></div>
                <div class="w-1 h-10 bg-emerald-600 rounded-full animate-pulse" style="animation-delay: 200ms;"></div>
                <div class="w-1 h-14 bg-emerald-500 rounded-full animate-pulse" style="animation-delay: 300ms;"></div>
                <div class="w-1 h-8 bg-emerald-600 rounded-full animate-pulse" style="animation-delay: 400ms;"></div>
            </div>
            
            <!-- Cancel Button (optional) -->
            <button @click="show = false" 
                    class="text-xs text-slate-500 hover:text-slate-700 underline">
                Press ESC to cancel
            </button>
        </div>
    </div>
    
    <!-- Prevent interaction -->
    <div class="absolute inset-0 cursor-wait"></div>
</div>

@once
@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
    
    /* Additional safety for loading overlay */
    .global-loading-overlay[x-cloak] {
        display: none !important;
    }
</style>
@endpush
@endonce
