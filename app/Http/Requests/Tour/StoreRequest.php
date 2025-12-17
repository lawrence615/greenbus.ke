<?php

namespace App\Http\Requests\Tour;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'location_id' => ['required', 'exists:locations,id'],
            'tour_category_id' => ['required', 'exists:tour_categories,id'],
            'title' => ['required', 'string', 'max:60'],
            'short_description' => ['required', 'string', 'max:600'],
            'description' => ['nullable', 'string'],
            'included' => ['nullable', 'string'],
            'excluded' => ['nullable', 'string'],
            'important_information' => ['nullable', 'string'],
            'duration_text' => ['required', 'string', 'max:100'],
            'duration_days' => ['nullable', 'integer', 'min:2', 'max:30'],
            'meeting_point' => ['nullable', 'string', 'max:255'],
            'starts_at_time' => ['nullable', 'string', 'max:10'],
            'cut_off_time' => ['required', 'integer', 'in:5,10,15,30,45,60'],
            'group_size_type' => ['required', 'string', 'in:3,8,custom'],
            'no_of_people' => ['required_if:group_size_type,custom', 'integer', 'min:1', 'max:100'],
            'is_daily' => ['boolean'],
            'is_featured' => ['boolean'],
            'base_price_senior' => ['required', 'numeric', 'min:0'],
            'base_price_adult' => ['required', 'numeric', 'min:0'],
            'base_price_child' => ['nullable', 'numeric', 'min:0'],
            'base_price_infant' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,published'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.type' => ['nullable', 'string', 'in:start,transit,stopover,activity,end'],
            'itinerary.*.title' => ['nullable', 'string', 'max:255'],
            'itinerary.*.description' => ['nullable', 'string'],
            'itinerary.*.duration_value' => ['nullable', 'integer', 'min:0'],
            'itinerary.*.duration_unit' => ['nullable', 'string', 'in:minutes,hours'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'cover_image_index' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Log all incoming data before validation
        Log::info('=== STORE REQUEST - BEFORE VALIDATION ===');
        Log::info('All request data:', $this->all());
        Log::info('Itinerary data specifically:', ['itinerary' => $this->input('itinerary')]);
        Log::info('Itinerary type:', ['type' => gettype($this->input('itinerary'))]);
        Log::info('Images data specifically:', ['images' => $this->input('images')]);
        Log::info('Images type:', ['type' => gettype($this->input('images'))]);
        if ($this->hasFile('images')) {
            Log::info('Has files:', ['files' => $this->file('images')]);
        } else {
            Log::info('No files detected');
        }
        Log::info('=== END PRE-VALIDATION LOG ===');
        
        // Filter out empty image values before validation
        $images = $this->input('images', []);
        if (is_array($images)) {
            $filteredImages = array_filter($images, function($image) {
                // Filter out null, empty, undefined, and "[object File]" strings
                return $image !== null && 
                       $image !== '' && 
                       $image !== 'undefined' && 
                       $image !== '[object File]' &&
                       $image !== '[object HTMLInputElement]';
            });
            $this->merge(['images' => array_values($filteredImages)]);
        }
        
        $this->merge([
            'is_daily' => $this->boolean('is_daily'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
        
        // Map group_size_type to no_of_people for non-custom options
        if ($this->filled('group_size_type') && $this->input('group_size_type') !== 'custom') {
            $this->merge([
                'no_of_people' => $this->input('group_size_type')
            ]);
        }
    }
}
