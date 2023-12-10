<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'photo' => 'required|max:100',
            'name' => 'required|max:100',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }
}
