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
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'regex:/^\+[1-9]\d{1,14}$/', 'max:20'],
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'special_requests' => ['nullable', 'string']
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
        ];
    }
}
