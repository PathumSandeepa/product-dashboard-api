<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'category' => 'sometimes|string|max:255',
            'image' => 'sometimes|string|max:255',
            'rating' => 'nullable|array',
            'rating.rate' => 'nullable|numeric|min:0|max:5',
            'rating.count' => 'nullable|integer|min:0',
        ];
    }
}
