<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
        if ($this->method() === 'POST') {
            return $this->store();
        }
        return $this->update();
    }

    private function store(): array
    {
        return [
            'product_id' => 'required',
            'qty' => 'required',
        ];
    }
    private function update(): array
    {
        return [
            'product_id' => 'required',
            'qty' => 'required',
            'status' => 'in:pending,process,paid',
        ];
    }
}
