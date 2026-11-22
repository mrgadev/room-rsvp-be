<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'room_id'       => ['required', 'integer', 'exists:rooms,id'],
            'checkin_date'  => ['required', 'date', 'after_or_equal:today'],
            'checkout_date' => ['required', 'date', 'after:checkin_date'],
        ];
    }

    public function messages() {
        return [
            'room_id.exists' => 'Room doesn\'t exists!',
            'room_id.required' => 'Room is required',
            'checkin_date.required' => 'Check in is required',
            'checkout_date.required' => 'Check out is required',
            'checkin_date.after_or_equal' => 'Check in must today or after that',
            'checkout_date.after' => 'Check out must be after checkin date'
        ];
    }
}
