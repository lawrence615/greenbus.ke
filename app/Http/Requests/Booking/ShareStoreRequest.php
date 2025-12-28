<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class ShareStoreRequest extends FormRequest
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
            'tour_id' => ['required', 'exists:tours,id'],
            'share_token' => ['required', 'string', 'exists:tours,share_token'],
            'first_name' => [
                'required', 
                'string', 
                'max:50',
                'regex:/^[A-Za-z\s\-\'\.]+$/',
                'min:2',
            ],
            'last_name' => [
                'required', 
                'string', 
                'max:50',
                'regex:/^[A-Za-z\s\-\'\.]+$/',
                'min:2',
            ],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{1,14}$/', 'max:20'],
            'tour_date' => ['required', 'date', 'after_or_equal:today'],
            'adults' => ['required', 'integer', 'min:1'],
            'youth' => ['nullable', 'integer', 'min:0'],
            'children' => ['nullable', 'integer', 'min:0'],
            'infants' => ['nullable', 'integer', 'min:0'],
            'seniors' => ['nullable', 'integer', 'min:0'],
            'special_requests' => [
                'nullable', 
                'string', 
                'max:1000',
                'regex:/^[A-Za-z0-9\s\.,\-\?!\'\":;()@\n\r]+$/',
                'min:5',
            ]
        ];
    }

    /**
     * Get the custom validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required',
            'phone.regex' => 'Please enter a valid international phone number (e.g., +254723384146)',
            'phone.max' => 'Phone number is too long',
            'first_name.required' => 'First name is required',
            'first_name.regex' => 'First name can only contain letters, spaces, hyphens, and apostrophes',
            'first_name.min' => 'First name must be at least 2 characters',
            'last_name.required' => 'Last name is required',
            'last_name.regex' => 'Last name can only contain letters, spaces, hyphens, and apostrophes',
            'last_name.min' => 'Last name must be at least 2 characters',
            'share_token.required' => 'Invalid share link',
            'share_token.exists' => 'Invalid share link',
            'special_requests.string' => 'Special requests must be text',
            'special_requests.max' => 'Special requests are too long (maximum 1000 characters)',
            'special_requests.regex' => 'Special requests contain invalid characters',
            'special_requests.min' => 'Special requests must be at least 5 characters long',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Merge first and last name for compatibility with existing booking system
        $this->merge([
            'customer_name' => trim($this->first_name . ' ' . $this->last_name),
            'customer_email' => $this->email,
            'customer_phone' => $this->phone,
            'date' => $this->tour_date,
            'youth' => $this->youth ?? 0,
            'children' => $this->children ?? 0,
            'infants' => $this->infants ?? 0,
            'seniors' => $this->seniors ?? 0,
        ]);
    }
}
