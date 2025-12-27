<?php

namespace App\Http\Requests\Tour\Itinerary;

use Illuminate\Foundation\Http\FormRequest;

class ReorderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_ids' => ['required', 'array'],
            'item_ids.*' => ['required', 'integer', 'exists:tour_itinerary_items,id'],
        ];
    }
}
