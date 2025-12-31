<?php

namespace App\Http\Requests\Booking\Share;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    private $hasGroupPricing = false;
    private $hasIndividualPricing = false;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get pricing type information for the tour.
     */
    private function determinePricingType(): void
    {
        $tour = \App\Models\Tour::where('id', $this->tour_id)->first();
        Log::info('Tour found for share_token: ' . $this->share_id, [
            'tour' => $tour
        ]);
        
        if ($tour) {
            $this->hasGroupPricing = $tour->pricings()->whereIn('person_type', ['adult', 'senior', 'youth', 'child', 'infant'])->exists();
            $this->hasIndividualPricing = $tour->pricings()->where('person_type', 'individual')->exists();
            
            // Debug logging
            Log::info('Pricing detection for tour ' . $tour->id, [
                'hasGroupPricing' => $this->hasGroupPricing,
                'hasIndividualPricing' => $this->hasIndividualPricing,
                'request_data' => $this->all()
            ]);
        } else {
            Log::error('Tour not found for share_token: ' . $this->share_token);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Determine pricing type consistently
        $this->determinePricingType();
        
        $rules = [
            'tour_id' => ['required', 'exists:tours,id'],
            'share_token' => ['required', 'string'],
            'customer_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-z\s\-\'\.]+$/',
                'min:2',
            ],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'regex:/^\+[1-9]\d{1,14}$/', 'max:20'],
            'date' => ['required', 'date', 'after_or_equal:today'],
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
        
        // Conditional validation based on pricing type
        if ($this->hasIndividualPricing && !$this->hasGroupPricing) {
            // Individual pricing only
            $rules['individuals'] = ['required', 'integer', 'min:1'];
            $rules['adults'] = ['nullable', 'integer', 'min:0'];
        } else {
            // Group pricing (or both types available)
            $rules['adults'] = ['required', 'integer', 'min:1'];
            $rules['individuals'] = ['nullable', 'integer', 'min:0'];
        }
        
        return $rules;
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
            'customer_name.regex' => 'Full name can only contain letters, spaces, hyphens, and apostrophes',
            'customer_name.min' => 'Full name must be at least 2 characters',
            'customer_name.max' => 'Full name is too long',
            'customer_email.required' => 'Email address is required',
            'customer_email.email' => 'Please enter a valid email address',
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
        Log::info($this->all());
        
        // Determine pricing type consistently
        $this->determinePricingType();
        
        // Set required field based on pricing type
        if ($this->hasIndividualPricing && !$this->hasGroupPricing) {
            // Individual pricing only - require individuals field
            $this->merge([
                'individuals' => $this->individuals ?? 1,
                'adults' => 0,
            ]);
        } else {
            // Group pricing - require adults field
            $this->merge([
                'adults' => $this->adults ?? 1,
                'individuals' => 0,
            ]);
        }
        
        // Set default values for optional fields
        $this->merge([
            'youth' => $this->youth ?? 0,
            'children' => $this->children ?? 0,
            'infants' => $this->infants ?? 0,
            'seniors' => $this->seniors ?? 0,
        ]);
    }
}
