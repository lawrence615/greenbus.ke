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
<div class="space-y-6">
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
    <form x-data="bespokeTourForm()" method="POST" action="{{ route('console.tours.bespoke.store') }}" class="space-y-6" x-init="init(@json(old()))">
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
                        value="{{ old('title') }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Enter tour title">
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Location *</label>
                    <select
                        name="location_id"
                        class="w-full px-3 py-2 h-[43px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        @change="updateLocation($event.target.value)">
                        <option value="">Select a location</option>
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
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
                            name="code_suffix"
                            value="{{ old('code_suffix') }}"
                            class="w-full px-3 py-2 border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            :class="locationCodePrefix() ? 'rounded-r-lg' : 'rounded-lg'"
                            placeholder="e.g., 001"
                            @input="updateCodeSuffix($event.target.value)"
                            :maxlength="maxCodeSuffixLength()">
                        <input type="hidden" name="code" value="{{ old('code') }}">
                    </div>
                    @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4 mt-6">
                <a href="{{ route('console.tours.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 cursor-pointer">
                    Create Bespoke Tour
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script type="application/json" id="location_codes">
{!! json_encode($locations->pluck('code', 'id')) !!}
</script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    function bespokeTourForm() {
        return {
            locationCodes: {},
            form: {
                title: '',
                location_id: '',
                code: '',
                code_suffix: '',
                description: ''
            },

            updateLocation(locationId) {
                console.log('updateLocation called with:', locationId);
                this.form.location_id = locationId;
                this.syncCombinedCode();
            },

            updateCodeSuffix(suffix) {
                console.log('updateCodeSuffix called with:', suffix);
                this.form.code_suffix = suffix;
                this.syncCombinedCode();
            },

            locationCodePrefix() {
                const id = this.form.location_id;
                const code = (this.locationCodes && id) ? (this.locationCodes[id] || '') : '';
                console.log('locationCodePrefix: id=', id, 'code=', code, 'locationCodes=', this.locationCodes);
                return code ? (code + '-') : '';
            },

            maxCodeSuffixLength() {
                const maxTotal = 20;
                const prefixLength = this.locationCodePrefix().length;
                const remaining = maxTotal - prefixLength;
                return remaining > 0 ? remaining : 0;
            },

            init(oldData) {
                const locationCodesEl = document.getElementById('location_codes');
                if (locationCodesEl) {
                    try {
                        this.locationCodes = JSON.parse(locationCodesEl.textContent || '{}');
                        console.log('Location codes loaded:', this.locationCodes);
                    } catch (e) {
                        console.error('Error parsing location codes:', e);
                        this.locationCodes = {};
                    }
                }

                // Initialize with old data if exists
                if (oldData && oldData.location_id) {
                    console.log('Initializing with old location:', oldData.location_id);
                    this.form.location_id = oldData.location_id;
                    this.updateLocation(oldData.location_id);
                }

                // Initialize Quill editor after component is mounted with delay
                setTimeout(() => {
                    this.initQuillEditor();
                }, 100);

                this.$watch('form.location_id', () => {
                    this.syncCombinedCode();
                });

                this.$watch('form.code_suffix', () => {
                    this.syncCombinedCode();
                });
            },

            syncCombinedCode() {
                const prefix = this.locationCodePrefix();
                const suffix = (this.form.code_suffix || '').trim();
                const combinedCode = (prefix && suffix) ? (prefix + suffix) : '';
                this.form.code = combinedCode;
                
                // Update the hidden input
                const codeInput = document.querySelector('input[name="code"]');
                if (codeInput) {
                    codeInput.value = combinedCode;
                }
                console.log('syncCombinedCode: prefix=', prefix, 'suffix=', suffix, 'combined=', combinedCode);
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

                // Initialize with old data if exists
                const oldDescription = document.querySelector('input[name="description"]').value;
                if (oldDescription) {
                    this.quillEditor.root.innerHTML = oldDescription;
                }

                // Sync Quill content with hidden input on text change
                this.quillEditor.on('text-change', () => {
                    const content = this.quillEditor.root.innerHTML;
                    this.form.description = content;
                    
                    // Update the hidden input
                    const descInput = document.querySelector('input[name="description"]');
                    if (descInput) {
                        descInput.value = content;
                    }
                    console.log('Description updated:', content);
                });
            }
        };
    }
</script>
@endpush