@extends('layouts.admin')

@section('title', 'Edit Bespoke Tour')

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
<div x-data="bespokeTourEditForm()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Edit Bespoke Tour</h1>
            <p class="text-sm text-slate-500">Update your bespoke tour details</p>
        </div>
        <a href="{{ route('console.tours.bespoke.show', $tour) }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Tour
        </a>
    </div>

    <form method="POST" action="{{ route('console.tours.bespoke.update', $tour) }}" x-ref="form" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-medium text-slate-900 mb-4">Basic Information</h2>

            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 lg:col-span-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tour Title *</label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title', $tour->title) }}"
                        x-model="form.title"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Enter tour title">
                    <p x-show="errors.title" class="mt-1 text-sm text-red-600" x-text="errors.title"></p>
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Location *</label>
                    <select
                        name="location_id"
                        x-model="form.location_id"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Select a location</option>
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ (string)old('location_id', $tour->location_id) === (string)$location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
                    <p x-show="errors.location_id" class="mt-1 text-sm text-red-600" x-text="errors.location_id"></p>
                    @error('location_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 lg:col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tour Code *</label>
                    <div class="flex">
                        <span
                            x-show="locationCodePrefix()"
                            x-text="locationCodePrefix()"
                            class="inline-flex items-center border border-r-0 border-slate-300 bg-slate-50 px-3 text-sm text-slate-700"
                            :class="locationCodePrefix() ? 'rounded-l-lg' : ''"></span>
                        <input
                            type="text"
                            x-model="form.code_suffix"
                            class="w-full px-3 py-2 border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            :class="locationCodePrefix() ? 'rounded-r-lg' : 'rounded-lg'"
                            placeholder="e.g., 001"
                            :maxlength="maxCodeSuffixLength()">
                        <input type="hidden" name="code" x-model="form.code" value="{{ old('code', $tour->code) }}">
                    </div>
                    <p x-show="errors.code" class="mt-1 text-sm text-red-600" x-text="errors.code"></p>
                    @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Description *</label>
                <div id="description_editor" class="bg-white rounded-lg border border-slate-300"></div>
                <input type="hidden" name="description" id="description" x-model="form.description" value="{{ old('description', $tour->description) }}">
                <p x-show="errors.description" class="mt-1 text-sm text-red-600" x-text="errors.description"></p>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('console.tours.bespoke.show', $tour) }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                Cancel
            </a>
            <button
                type="button"
                @click="submitForm()"
                :disabled="isSubmitting"
                class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                <span x-show="!isSubmitting">Update Bespoke Tour</span>
                <span x-show="isSubmitting">Updating...</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script type="application/json" id="location_codes">{!! json_encode($locations->pluck('code', 'id')) !!}</script>
<script type="application/json" id="bespoke_tour">{!! json_encode(['title' => old('title', $tour->title), 'location_id' => old('location_id', $tour->location_id), 'code' => old('code', $tour->code), 'description' => old('description', $tour->description)]) !!}</script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    function bespokeTourEditForm() {
        return {
            locationCodes: {},
            form: {
                title: '',
                location_id: '',
                code: '',
                code_suffix: '',
                description: ''
            },
            errors: {},
            isSubmitting: false,
            quillEditor: null,

            locationCodePrefix() {
                const id = this.form.location_id;
                const code = (this.locationCodes && id) ? (this.locationCodes[id] || '') : '';
                return code ? (code + '-') : '';
            },

            maxCodeSuffixLength() {
                const maxTotal = 20;
                const prefixLength = this.locationCodePrefix().length;
                const remaining = maxTotal - prefixLength;
                return remaining > 0 ? remaining : 0;
            },

            init() {
                const locationCodesEl = document.getElementById('location_codes');
                if (locationCodesEl) {
                    try {
                        this.locationCodes = JSON.parse(locationCodesEl.textContent || '{}');
                    } catch (e) {
                        this.locationCodes = {};
                    }
                }

                const tourEl = document.getElementById('bespoke_tour');
                if (tourEl) {
                    try {
                        const tour = JSON.parse(tourEl.textContent || '{}');
                        this.form.title = tour.title || '';
                        this.form.location_id = String(tour.location_id || '');
                        this.form.description = tour.description || '';
                        this.form.code = tour.code || '';
                    } catch (e) {
                    }
                }

                this.syncSuffixFromCode();

                setTimeout(() => {
                    this.initQuillEditor();
                }, 100);

                this.$watch('form.location_id', () => {
                    this.syncCombinedCode();
                    this.syncSuffixFromCode();
                });

                this.$watch('form.code_suffix', () => {
                    this.syncCombinedCode();
                });
            },

            syncSuffixFromCode() {
                const prefix = this.locationCodePrefix();
                if (!prefix) {
                    this.form.code_suffix = '';
                    this.form.code = '';
                    return;
                }

                const current = (this.form.code || '').trim();
                if (current && current.indexOf(prefix) === 0) {
                    this.form.code_suffix = current.substring(prefix.length);
                }

                this.syncCombinedCode();
            },

            syncCombinedCode() {
                const prefix = this.locationCodePrefix();
                const suffix = (this.form.code_suffix || '').trim();
                this.form.code = (prefix && suffix) ? (prefix + suffix) : '';
            },

            initQuillEditor() {
                const container = document.getElementById('description_editor');
                if (container) {
                    container.innerHTML = '';
                }

                if (this.quillEditor) {
                    this.quillEditor = null;
                }

                const toolbarOptions = [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ];

                this.quillEditor = new Quill('#description_editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: toolbarOptions
                    }
                });

                if (this.form.description) {
                    this.quillEditor.root.innerHTML = this.form.description;
                }

                this.quillEditor.on('text-change', () => {
                    this.form.description = this.quillEditor.root.innerHTML;
                });
            },

            submitForm() {
                this.isSubmitting = true;
                this.errors = {};

                if (this.quillEditor) {
                    this.form.description = this.quillEditor.root.innerHTML;
                }

                if (!this.form.title.trim()) {
                    this.errors.title = 'Title is required';
                }
                if (this.form.title.length > 60) {
                    this.errors.title = 'Title must not exceed 60 characters.';
                }
                if (!this.form.location_id) {
                    this.errors.location_id = 'Select a location';
                }
                if (!this.form.code_suffix.trim()) {
                    this.errors.code = 'Tour code is required';
                }
                if (!this.form.description.trim() || this.form.description === '<p><br></p>') {
                    this.errors.description = 'Description is required';
                }
                if (this.form.description.length > 65535) {
                    this.errors.description = 'Description has exceeded the maximum allowed length.';
                }

                if (Object.keys(this.errors).length > 0) {
                    this.isSubmitting = false;
                    return;
                }

                this.syncCombinedCode();

                if (!this.form.code.trim()) {
                    this.errors.code = 'Tour code is required';
                    this.isSubmitting = false;
                    return;
                }

                this.$refs.form.submit();
            }
        };
    }
</script>
@endpush
