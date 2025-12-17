<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

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
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['nullable', 'string', 'max:50'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['nullable', 'integer', 'min:0'],
            'infants' => ['nullable', 'integer', 'min:0'],
            'seniors' => ['nullable', 'integer', 'min:0'],
            'customer_name' => [
                'required', 
                'string', 
                'max:100',
                'regex:/^[A-Za-z\s\-\'\.]+$/', // Only letters, spaces, hyphens, apostrophes, periods
                'min:2', // Minimum 2 characters
            ],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'regex:/^\+[1-9]\d{1,14}$/', 'max:20'],
            'country_of_origin' => [
                'nullable', 
                'string', 
                'max:100',
                'regex:/^[A-Za-z\s\-\'\.]+$/', // Only letters, spaces, hyphens, apostrophes, periods
                'min:2', // Minimum 2 characters
            ],
            'special_requests' => [
                'nullable', 
                'string', 
                'max:1000',
                'regex:/^[A-Za-z0-9\s\.,\-\?!\'\":;()@\n\r]+$/', // Allow letters, numbers, basic punctuation and newlines
                'min:5', // Minimum 5 characters for meaningful requests
            ]
        ];
    }

    /**
     * Get the custom validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'customer_phone.required' => 'Phone number is required',
            'customer_phone.regex' => 'Please enter a valid international phone number (e.g., +254723384146)',
            'customer_phone.max' => 'Phone number is too long',
            'customer_name.required' => 'Full name is required',
            'customer_name.string' => 'Name must be text',
            'customer_name.max' => 'Name is too long',
            'customer_name.regex' => 'Name can only contain letters, spaces, hyphens, and apostrophes',
            'customer_name.min' => 'Name must be at least 2 characters',
            'country_of_origin.string' => 'Country of origin must be text',
            'country_of_origin.max' => 'Country name is too long',
            'country_of_origin.regex' => 'Country name can only contain letters, spaces, hyphens, and apostrophes',
            'country_of_origin.min' => 'Country name must be at least 2 characters',
            'special_requests.string' => 'Special requests must be text',
            'special_requests.max' => 'Special requests are too long (maximum 1000 characters)',
            'special_requests.regex' => 'Special requests contain invalid characters',
            'special_requests.min' => 'Special requests must be at least 5 characters long',
        ];
    }
}
