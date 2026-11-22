<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateRoomRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,avif,webp'],
            'description' => ['string', 'nullable'],
            'price' => ['required'],
            'total_units' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'category_id.exists' => 'Category ID must exists',
            'category_id.required' => 'Category ID is required',
            'image.mimes' => 'Image must in PNG, JPG, AVIF and WEBP',
            'price.required' => 'Price is required',
            'total_units.required' => 'Total units is required'
        ];
    }
}
