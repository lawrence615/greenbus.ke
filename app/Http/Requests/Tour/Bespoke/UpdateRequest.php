<?php

namespace App\Http\Requests\Tour\Bespoke;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tourId = $this->route('tour')?->id;

        return [
            'title' => 'required|string|max:60',
            'location_id' => 'required|exists:locations,id',
            'code' => 'required|string|max:20|unique:tours,code,' . $tourId,
            'description' => 'required|string|max:65535',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tour title is required.',
            'title.max' => 'Tour title must not exceed 255 characters.',
            'location_id.required' => 'Location is required.',
            'location_id.exists' => 'Selected location is invalid.',
            'code.required' => 'Tour code is required.',
            'code.max' => 'Tour code must not exceed 20 characters.',
            'code.unique' => 'Tour code has already been taken.',
            'description.required' => 'Description is required.',
            'description.max' => 'Description has exceeded the maximum allowed length.',
        ];
    }
}
