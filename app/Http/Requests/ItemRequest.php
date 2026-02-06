<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
        $maxFileUrlSize = env('FILE_URL_SIZE') * 1024;
        $maxPrintFileUrlSize = env('PRINT_FILE_URL_SIZE') * 1024;
        $maxImageCoverSize = env('IMAGE_COVER_SIZE') * 1024;

        return [
            // 'sku' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|exists:item_categories,id',
            'image_cover' => 'nullable|file|mimes:png,jpg',
            // 'image_cover' => 'nullable|file|mimes:png,jpg|max:'. $maxImageCoverSize,
            'price' => 'nullable|numeric|min:0',
            'minimum_stock' => 'nullable|numeric|min:0',
            'is_inventory' => 'nullable',
        ];
    }
}
