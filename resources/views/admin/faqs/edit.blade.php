@extends('layouts.admin')

@section('title', 'Edit FAQ')
@section('page-title', 'Edit FAQ')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor { min-height: 120px; }
    .ql-container { font-size: 14px; border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem; }
    .ql-toolbar { border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem; }
</style>
@endpush

@section('content')
<div class="max-w-3xl">
    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ route('console.faqs.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to FAQs
        </a>
    </div>

    <form method="POST" action="{{ route('console.faqs.update', $faq) }}" class="space-y-6" x-data="faqForm()" @submit="syncEditor">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">FAQ Content</h2>
            
            <!-- Question -->
            <div class="space-y-1.5 mb-4">
                <label for="question" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                    Question <span class="text-red-500">*</span>
                    <span class="relative group">
                        <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">The question customers frequently ask</span>
                    </span>
                </label>
                <input 
                    type="text" 
                    name="question" 
                    id="question"
                    value="{{ old('question', $faq->question) }}"
                    placeholder="e.g., How do I book a tour?"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('question') border-red-500 @enderror"
                    required
                >
                @error('question')
                <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Answer -->
            <div class="space-y-1.5">
                <label for="answer" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                    Answer <span class="text-red-500">*</span>
                    <span class="relative group">
                        <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">A clear, helpful response to the question</span>
                    </span>
                </label>
                <input type="hidden" name="answer" id="answer" value="{{ old('answer', $faq->answer) }}">
                <div id="answer-editor" class="bg-white rounded-lg border border-slate-300 @error('answer') border-red-500 @enderror"></div>
                @error('answer')
                <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Settings</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <!-- Category -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="category" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        General Category
                        <span class="relative group">
                            <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">Group related FAQs (e.g., Booking, Payment)</span>
                        </span>
                    </label>
                    <input 
                        type="text" 
                        name="category" 
                        id="category"
                        value="{{ old('category', $faq->category) }}"
                        placeholder="e.g., Booking, Tours, Payment"
                        list="category-suggestions"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('category') border-red-500 @enderror"
                    >
                    <datalist id="category-suggestions">
                        @php
                            $allCategories = array_unique(array_merge($categories, ['Booking', 'Tours', 'Payment', 'General']));
                            sort($allCategories);
                        @endphp
                        @foreach($allCategories as $cat)
                        <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                    @error('category')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tour Category -->
                <div class="space-y-1.5">
                    <label for="tour_category_id" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Tour Category
                        <span class="relative group">
                            <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">Link FAQ to a specific tour type</span>
                        </span>
                    </label>
                    <select 
                        name="tour_category_id" 
                        id="tour_category_id"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('tour_category_id') border-red-500 @enderror"
                    >
                        <option value="">— None (General FAQ) —</option>
                        @foreach($tourCategories as $tourCategory)
                        <option value="{{ $tourCategory->id }}" {{ old('tour_category_id', $faq->tour_category_id) == $tourCategory->id ? 'selected' : '' }}>
                            {{ $tourCategory->name }}
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-500">Optional: Link to a tour category</p>
                    @error('tour_category_id')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Sort Order -->
                <div class="space-y-1.5">
                    <label for="sort_order" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Sort Order
                        <span class="relative group">
                            <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">Controls display order; lower numbers appear first</span>
                        </span>
                    </label>
                    <input 
                        type="number" 
                        name="sort_order" 
                        id="sort_order"
                        value="{{ old('sort_order', $faq->sort_order) }}"
                        min="0"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('sort_order') border-red-500 @enderror"
                    >
                    <p class="text-xs text-slate-500">Lower numbers appear first</p>
                    @error('sort_order')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Status
                        <span class="relative group">
                            <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">Only active FAQs are shown on the public website</span>
                        </span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer h-[38px]">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            {{ old('is_active', $faq->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                        >
                        <span class="text-sm text-slate-700">Active (visible on website)</span>
                    </label>
                    <p class="text-xs text-slate-500">Only active FAQs are visible</p>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('console.faqs.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 cursor-pointer">
                Update FAQ
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
function faqForm() {
    return {
        editor: null,
        
        init() {
            this.initEditor();
        },
        
        initEditor() {
            const toolbarOptions = [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ];
            
            this.editor = new Quill('#answer-editor', {
                theme: 'snow',
                modules: { toolbar: toolbarOptions },
                placeholder: 'Provide a clear and helpful answer...'
            });
            
            // Set initial content
            const initialContent = document.getElementById('answer').value;
            if (initialContent) {
                this.editor.root.innerHTML = initialContent;
            }
        },
        
        syncEditor() {
            if (this.editor) {
                document.getElementById('answer').value = this.editor.root.innerHTML;
            }
        }
    };
}
</script>
@endpush
