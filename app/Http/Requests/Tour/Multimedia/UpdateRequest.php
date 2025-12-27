<?php

namespace App\Http\Requests\Tour\Multimedia;

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
            'images' => ['required', 'array'],
            'images.*.id' => ['required', 'integer', 'exists:tour_images,id'],
            'images.*.caption' => ['nullable', 'string', 'max:255'],
            'images.*.sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
