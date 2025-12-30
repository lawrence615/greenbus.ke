<?php

namespace App\Http\Requests\Tour\Bespoke;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:60',
            'location_id' => 'required|exists:locations,id',
            'code_suffix' => 'required|string|max:10',
            'code' => 'required|string|max:20|unique:tours,code',
            'description' => 'required|string|max:65535',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Tour title is required.',
            'title.max' => 'Tour title must not exceed 60 characters.',
            'location_id.required' => 'Location is required.',
            'location_id.exists' => 'Selected location is invalid.',
            'code.required' => 'Tour code is required.',
            'code_suffix.required' => 'Tour code suffix is required.',
            'code_suffix.exists' => 'Selected tour code suffix is invalid.',
            'code.max' => 'Tour code must not exceed 20 characters.',
            'code.unique' => 'Tour code has already been taken.',
            'description.required' => 'Description is required.',
            'description.max' => 'Description has exceeded the maximum allowed length.',
        ];
    }

    protected function prepareForValidation()
    {
        Log::info('All request data:', $this->all());
    }
}
