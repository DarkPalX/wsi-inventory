<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
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
            'ref_no' => 'required',
            'ris_no' => 'nullable',
            'date_ordered' => 'required',
            'remarks' => 'nullable',
            'item_id' => 'required',
            'sku' => 'required',
            'quantity' => 'required',
            'net_total' => 'required',
            'vat' => 'required',
            'grand_total' => 'required',
        ];
    }
}
