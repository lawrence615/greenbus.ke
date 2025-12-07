<?php

namespace App\Http\Requests\Testimonial;

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
            'tour_id' => ['nullable', 'exists:tours,id'],
            'author_name' => ['required', 'string', 'max:255'],
            'author_location' => ['nullable', 'string', 'max:255'],
            'author_date' => ['nullable', 'date'],
            'author_cover' => ['nullable', 'url', 'max:2048'],
            'tour_name' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'travel_type' => ['nullable', 'string', 'max:100'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'sort_order' => $this->sort_order ?? 0,
        ]);
    }
}
