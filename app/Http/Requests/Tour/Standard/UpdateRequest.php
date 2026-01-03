<?php

namespace App\Http\Requests\Tour\Standard;

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
            'location_id' => ['required', 'exists:locations,id'],
            'tour_category_id' => ['required', 'exists:tour_categories,id'],
            'title' => ['required', 'string', 'max:80'],
            'short_description' => ['required', 'string', 'max:600'],
            'description' => ['nullable', 'string'],
            'included' => ['nullable', 'string'],
            'excluded' => ['nullable', 'string'],
            'additional_information' => ['nullable', 'string'],
            'cancellation_policy' => ['nullable', 'string'],
            'duration_text' => ['required', 'string', 'max:100'],
            'duration_days' => ['nullable', 'integer', 'min:2', 'max:30'],
            'meeting_point' => ['nullable', 'string', 'max:255'],
            'starts_at_time' => ['nullable', 'string', 'max:10'],
            'cut_off_time' => ['required', 'integer', 'in:5,10,15,30,45,60'],
            'is_daily' => ['boolean'],
            'is_featured' => ['boolean'],
            'pricing' => ['required', 'array'],
            'pricing.adult.price' => ['required', 'numeric', 'min:0'],
            'pricing.adult.person_type' => ['required', 'string', 'in:adult'],
            'pricing.senior.price' => ['nullable', 'numeric', 'min:0'],
            'pricing.senior.person_type' => ['nullable', 'string', 'in:senior'],
            'pricing.youth.price' => ['nullable', 'numeric', 'min:0'],
            'pricing.youth.person_type' => ['nullable', 'string', 'in:youth'],
            'pricing.child.price' => ['nullable', 'numeric', 'min:0'],
            'pricing.child.person_type' => ['nullable', 'string', 'in:child'],
            'pricing.infant.price' => ['nullable', 'numeric', 'min:0'],
            'pricing.infant.person_type' => ['nullable', 'string', 'in:infant'],
            'status' => ['required', 'in:draft,published'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.type' => ['nullable', 'string', 'in:start,transit,stopover,activity,end'],
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
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }
}
