<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'rating' => 'nullable|array',
            'rating.rate' => 'nullable|numeric|min:0|max:5',
            'rating.count' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return ['title.required' => 'The product title is required.'];
    }
}
