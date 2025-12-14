<?php

namespace App\Http\Requests\Tour;

use Illuminate\Foundation\Http\FormRequest;

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
            'short_description' => ['required', 'string', 'max:250'],
            'description' => ['nullable', 'string'],
            'included' => ['nullable', 'string'],
            'excluded' => ['nullable', 'string'],
            'important_information' => ['nullable', 'string'],
            'duration_text' => ['required', 'string', 'max:100'],
            'duration_days' => ['nullable', 'integer', 'min:2', 'max:30'],
            'meeting_point' => ['nullable', 'string', 'max:255'],
            'starts_at_time' => ['nullable', 'string', 'max:10'],
            'cut_off_time' => ['required', 'integer', 'in:5,10,15,30,45,60'],
            'is_daily' => ['boolean'],
            'featured' => ['boolean'],
            'base_price_senior' => ['required', 'numeric', 'min:0'],
            'base_price_adult' => ['required', 'numeric', 'min:0'],
            'base_price_child' => ['nullable', 'numeric', 'min:0'],
            'base_price_infant' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,published'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.type' => ['nullable', 'string', 'in:start,transit,stopover,activity,end'],
            'itinerary.*.title' => ['required_with:itinerary', 'string', 'max:255'],
            'itinerary.*.description' => ['nullable', 'string'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'cover_image_index' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_daily' => $this->boolean('is_daily'),
            'featured' => $this->boolean('featured'),
        ]);
    }
}
