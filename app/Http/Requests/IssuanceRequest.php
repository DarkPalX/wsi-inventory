<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssuanceRequest extends FormRequest
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
            'technical_report_no' => 'nullable',
            'ris_no' => 'required',
            'vehicle_id' => 'nullable',
            'date_released' => 'required',
            'remarks' => 'nullable',
            'item_id' => 'required',
            'sku' => 'required',
            'quantity' => 'required',
            'cost' => 'required',
            'price' => 'required',
            'net_total' => 'required',
            'actual_receiver' => 'nullable',
        ];
    }
}
