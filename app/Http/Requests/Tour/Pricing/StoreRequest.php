<?php

namespace App\Http\Requests\Tour\Pricing;

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
            'tour_id' => 'required|exists:tours,id',
            'person_type' => 'required|string|in:adult,senior,youth,child,infant,individual|unique:tour_pricings,person_type,NULL,id,tour_id,' . $this->input('tour_id'),
            'price' => 'required|numeric|min:5',
            'discount' => 'nullable|integer|min:0|lt:price',
            'discounted_price' => 'nullable|numeric|min:0',
        ];
    }

    // protected function prepareForValidation()
    // {
    //     Log::info('prepareForValidation', ['data' => $this->all()]);
    //     return parent::prepareForValidation();
    // }

    protected function prepareForValidation()
    {
        $price = (float) $this->input('price');
        $discount = (int) $this->input('discount', 0);
        $discountedPrice = max(0, $price - $discount);

        $this->merge([
            'discounted_price' => $discountedPrice,
        ]);

        Log::info('prepareForValidation', ['data' => $this->all()]);
        return parent::prepareForValidation();
    }

    public function messages()
    {
        return [
            'price.min' => 'The price must be at least :min.',
            'discount.lt' => 'The discount must be less than the price.',
            'person_type.unique' => 'A pricing for this person type already exists for this tour.',
        ];
    }
}
