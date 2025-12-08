<?php

namespace App\Http\Requests\Tour;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city_id' => ['required', 'exists:cities,id'],
            'tour_category_id' => ['nullable', 'exists:tour_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'includes' => ['nullable', 'string'],
            'important_information' => ['nullable', 'string'],
            'duration_text' => ['nullable', 'string', 'max:100'],
            'meeting_point' => ['nullable', 'string', 'max:255'],
            'starts_at_time' => ['nullable', 'string', 'max:10'],
            'is_daily' => ['boolean'],
            'featured' => ['boolean'],
            'base_price_adult' => ['required', 'numeric', 'min:0'],
            'base_price_child' => ['nullable', 'numeric', 'min:0'],
            'base_price_infant' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,published'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.title' => ['required_with:itinerary', 'string', 'max:255'],
            'itinerary.*.description' => ['nullable', 'string'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'cover_image_id' => ['nullable', 'integer', 'exists:tour_images,id'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:tour_images,id'],
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
