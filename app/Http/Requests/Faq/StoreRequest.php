<?php

namespace App\Http\Requests\FAQ;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:500'],
            'answer' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Strip HTML tags and check text length
                    $plainText = strip_tags($value);
                    $textLength = Str::length(trim($plainText));
                    
                    if ($textLength > 300) {
                        $fail('The answer may not be greater than 300 characters.');
                    }
                    
                    if (empty(trim($plainText))) {
                        $fail('The answer field is required.');
                    }
                }
            ],
            'category' => ['nullable', 'string', 'max:40'],
            'tour_category_id' => ['nullable', 'exists:tour_categories,id'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'sort_order' => $this->sort_order ?? 0,
        ]);
    }
}
