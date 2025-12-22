@extends('layouts.admin')

@section('title', 'Create Bespoke Tour')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor {
        min-height: 120px;
    }

    .ql-container {
        font-size: 14px;
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

    .ql-toolbar {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
</style>
@endpush

@section('content')
<div x-data="bespokeTourForm()" class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Create Bespoke Tour</h1>
            <p class="text-sm text-slate-500">Create a custom, personalized tour experience</p>
        </div>
        <a href="{{ route('console.tours.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Tours
        </a>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('console.tours.bespoke.store') }}" x-ref="form" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-medium text-slate-900 mb-4">Basic Information</h2>

            <!-- Title, Location, Code -->
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 lg:col-span-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tour Title *</label>
                    <input
                        type="text"
                        name="title"
                        x-model="form.title"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Enter tour title">
                    <p x-show="errors.title" class="mt-1 text-sm text-red-600" x-text="errors.title"></p>
                </div>

                <div class="col-span-12 lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Location *</label>
                    <select
                        name="location_id"
                        x-model="form.location_id"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Select a location</option>
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                    <p x-show="errors.location_id" class="mt-1 text-sm text-red-600" x-text="errors.location_id"></p>
                </div>

                <div class="col-span-12 lg:col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tour Code *</label>
                    <input
                        type="text"
                        name="code"
                        x-model="form.code"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="e.g., BESPOKE-001"
                        maxlength="20">
                    <p x-show="errors.code" class="mt-1 text-sm text-red-600" x-text="errors.code"></p>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Description *</label>
                <div id="description_editor" class="bg-white rounded-lg border border-slate-300"></div>
                <input type="hidden" name="description" id="description" value="{{ old('description') }}">
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('console.tours.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                Cancel
            </a>
            <button
                type="button"
                @click="submitForm()"
                :disabled="isSubmitting"
                class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!isSubmitting">Create Bespoke Tour</span>
                <span x-show="isSubmitting">Creating...</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    function bespokeTourForm() {
        return {
            form: {
                title: '',
                location_id: '',
                code: '',
                description: ''
            },
            errors: {},
            isSubmitting: false,
            quillEditor: null,

            init() {
                // Initialize Quill editor after component is mounted with delay
                setTimeout(() => {
                    this.initQuillEditor();
                }, 100);
            },

            initQuillEditor() {
                // Clear any existing content and destroy previous instance
                const container = document.getElementById('description_editor');
                if (container) {
                    container.innerHTML = '';
                }

                // Destroy existing Quill instance if it exists
                if (this.quillEditor) {
                    this.quillEditor = null;
                }

                const toolbarOptions = [
                    ['bold', 'italic', 'underline'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['link'],
                    ['clean']
                ];

                this.quillEditor = new Quill('#description_editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: toolbarOptions
                    }
                });

                // Sync Quill content with Alpine.js data
                this.quillEditor.on('text-change', () => {
                    this.form.description_editor = this.quillEditor.root.innerHTML;
                });
            },

            submitForm() {
                this.isSubmitting = true;
                this.errors = {};

                // Ensure description is synced from Quill
                if (this.quillEditor) {
                    this.form.description = this.quillEditor.root.innerHTML;
                }

                // Basic validation
                if (!this.form.title.trim()) {
                    this.errors.title = 'Title is required';
                }
                if (!this.form.location_id) {
                    this.errors.location_id = 'Location is required';
                }
                if (!this.form.code.trim()) {
                    this.errors.code = 'Tour code is required';
                }
                if (!this.form.description.trim() || this.form.description === '<p><br></p>') {
                    this.errors.description = 'Description is required';
                }

                if (Object.keys(this.errors).length > 0) {
                    this.isSubmitting = false;
                    return;
                }

                // Submit form
                this.$refs.form.submit();
            }
        };
    }
</script>
@endpush