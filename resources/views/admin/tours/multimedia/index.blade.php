@extends('layouts.admin')

@section('title', 'Manage Images - ' . $tour->title)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('console.dashboard') }}" class="text-slate-500 hover:text-slate-700">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('console.tours.index') }}" class="ml-1 text-slate-500 hover:text-slate-700 md:ml-2">
                                Tours
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('console.tours.show', $tour) }}" class="ml-1 text-slate-500 hover:text-slate-700 md:ml-2">
                                {{ $tour->title }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-slate-500 md:ml-2">Images</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('console.tours.show', $tour) }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Tour
            </a>
            <a href="{{ route('console.tours.itinerary.index', $tour) }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Manage Itinerary
            </a>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
    <div class="flex">
        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
    <div class="flex">
        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>
        <p class="ml-3 text-sm text-red-800">{{ session('error') }}</p>
    </div>
</div>
@endif

<!-- Tour Images -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200">
        <!-- <h2 class="font-semibold text-slate-900">Tour Images</h2> -->
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Manage Images</h1>
        <!-- <p class="text-sm text-slate-500 mt-1">Manage tour images. Click on an image to set it as cover.</p> -->
        <p class="text-slate-600 mt-1">Upload and manage tour images for "{{ $tour->title }}". Click on an image to set it as cover.</p>
    </div>
    <div class="p-6">
        <div x-data="imageEditor()" class="space-y-6">
            <!-- Existing Images -->
            @if($images->count() > 0)
            <div>
                <h3 class="text-sm font-medium text-slate-700 mb-3">Current Images</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($images as $image)
                    <div class="relative group aspect-square rounded-lg overflow-hidden border-2 transition-colors cursor-pointer"
                        :class="coverImageId === {{ $image->id }} ? 'border-emerald-500 ring-2 ring-emerald-500/20' : (deleteImages.includes({{ $image->id }}) ? 'border-red-500 opacity-50' : 'border-slate-200')"
                        @click="setCover({{ $image->id }})">
                        <img src="{{ $image->url }}" class="w-full h-full object-cover" alt="Tour image">

                        <!-- Cover Badge -->
                        <div x-show="coverImageId === {{ $image->id }}" class="absolute top-2 left-2 px-2 py-1 bg-emerald-500 text-white text-xs font-medium rounded">
                            Cover
                        </div>

                        <!-- Delete Badge -->
                        <div x-show="deleteImages.includes({{ $image->id }})" class="absolute top-2 left-2 px-2 py-1 bg-red-500 text-white text-xs font-medium rounded">
                            Will be deleted
                        </div>

                        <!-- Overlay Actions -->
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <button type="button" @click.stop="setCover({{ $image->id }})" x-show="coverImageId !== {{ $image->id }} && !deleteImages.includes({{ $image->id }})"
                                class="p-2 bg-white rounded-full text-slate-700 hover:bg-emerald-50 hover:text-emerald-600" title="Set as cover">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                            <button type="button" @click.stop="toggleDelete({{ $image->id }})"
                                class="p-2 bg-white rounded-full text-slate-700 hover:bg-red-50 hover:text-red-600" :title="deleteImages.includes({{ $image->id }}) ? 'Restore' : 'Delete'">
                                <svg x-show="!deleteImages.includes({{ $image->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <svg x-show="deleteImages.includes({{ $image->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Upload New Images -->
            <div>
                <h3 class="text-sm font-medium text-slate-700 mb-3">Add New Images</h3>
                <form action="{{ route('console.tours.multimedia.upload', $tour) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop($event)"
                        :class="isDragging ? 'border-emerald-500 bg-emerald-50' : 'border-slate-300 hover:border-slate-400'"
                        class="border-2 border-dashed rounded-xl p-6 text-center transition-colors cursor-pointer"
                        @click="$refs.fileInput.click()"
                    >
                        <input
                            type="file"
                            name="images[]"
                            multiple
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                            class="hidden"
                            x-ref="fileInput"
                            @change="handleFiles($event.target.files)"
                        >
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <p class="text-sm text-slate-600">Drop images here or click to upload</p>
                            <p class="text-xs text-slate-500">JPEG, PNG, WebP up to 5MB each</p>
                        </div>
                    </div>

                    <!-- New Image Previews -->
                    <div x-show="previews.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative group aspect-square rounded-lg overflow-hidden border-2 border-slate-200">
                                <img :src="preview.url" class="w-full h-full object-cover">
                                <div class="absolute top-2 left-2 px-2 py-1 bg-blue-500 text-white text-xs font-medium rounded">New</div>
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" @click="removeNewImage(index)"
                                        class="p-2 bg-white rounded-full text-slate-700 hover:bg-red-50 hover:text-red-600" title="Remove">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Upload Button -->
                    <div x-show="previews.length > 0" class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Upload Images
                        </button>
                    </div>
                </form>
            </div>

            <!-- Hidden inputs -->
            <input type="hidden" name="cover_image_id" :value="coverImageId">
            <template x-for="id in deleteImages" :key="id">
                <input type="hidden" name="delete_images[]" :value="id">
            </template>
        </div>
    </div>
</div>
</div>

<script>
function imageEditor() {
    return {
        isDragging: false,
        previews: [],
        files: [],
        deleteImages: [],
        coverImageId: {{ $images->where('is_cover', true)->first()?->id ?? $images->first()?->id ?? 'null' }},
        originalCoverId: {{ $images->where('is_cover', true)->first()?->id ?? $images->first()?->id ?? 'null' }},
        maxSize: 5 * 1024 * 1024, // 5MB

        handleDrop(event) {
            this.isDragging = false;
            const files = event.dataTransfer.files;
            this.handleFiles(files);
        },

        handleFiles(fileList) {
            const newFiles = Array.from(fileList).filter(file => {
                if (!file.type.match(/^image\/(jpeg|png|jpg|webp)$/)) {
                    alert(`${file.name} is not a supported image format`);
                    return false;
                }
                if (file.size > this.maxSize) {
                    alert(`${file.name} is too large (max 5MB)`);
                    return false;
                }
                return true;
            });

            newFiles.forEach(file => {
                this.files.push(file);
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previews.push({
                        url: e.target.result,
                        name: file.name
                    });
                };
                reader.readAsDataURL(file);
            });

            this.updateFileInput();
        },

        removeNewImage(index) {
            this.files.splice(index, 1);
            this.previews.splice(index, 1);
            this.updateFileInput();
        },

        setCover(imageId) {
            this.coverImageId = imageId;
            // Save cover image immediately
            this.saveCoverImage(imageId);
        },

        async saveCoverImage(imageId) {
            try {
                const response = await fetch('{{ route("console.tours.multimedia.set-cover", $tour) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        image_id: imageId
                    })
                });
                
                const data = await response.json();
                if (!data.success) {
                    // Revert if failed
                    this.coverImageId = this.originalCoverId;
                    alert('Failed to set cover image. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                // Revert if failed
                this.coverImageId = this.originalCoverId;
                alert('Failed to set cover image. Please try again.');
            }
        },

        toggleDelete(imageId) {
            const index = this.deleteImages.indexOf(imageId);
            if (index > -1) {
                this.deleteImages.splice(index, 1);
            } else {
                this.deleteImages.push(imageId);
                // If deleting the cover, reset cover to another image
                if (this.coverImageId === imageId) {
                    const remainingImages = @json($images->pluck('id')).filter(id => !this.deleteImages.includes(id));
                    this.coverImageId = remainingImages[0] || null;
                }
            }
        },

        updateFileInput() {
            const dt = new DataTransfer();
            this.files.forEach(file => dt.items.add(file));
            this.$refs.fileInput.files = dt.files;
        }
    }
}
</script>
</div>
@endsection