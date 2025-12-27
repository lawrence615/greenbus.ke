<?php

namespace App\Http\Requests\Tour\Itinerary;

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
            'type' => ['required', 'string', 'in:start,transit,stopover,activity,end'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_value' => ['nullable', 'integer', 'min:0'],
            'duration_unit' => ['nullable', 'string', 'in:minutes,hours'],
        ];
    }
}
