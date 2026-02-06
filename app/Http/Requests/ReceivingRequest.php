<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceivingRequest extends FormRequest
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
            'po_number' => 'required',
            'po_number' => 'required',
            'si_number' => 'nullable',
            'date_received' => 'required',
            'remarks' => 'nullable',
            'item_id' => 'required',
            'price' => 'required',
            'sku' => 'required',
            'order' => 'required',
            'quantity' => 'required',
        ];
    }
}
