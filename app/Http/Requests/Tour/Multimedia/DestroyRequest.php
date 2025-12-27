<?php

namespace App\Http\Requests\Tour\Multimedia;

use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image_id' => ['required', 'integer', 'exists:tour_images,id'],
        ];
    }
}
