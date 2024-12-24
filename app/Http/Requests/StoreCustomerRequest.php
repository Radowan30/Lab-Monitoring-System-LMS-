<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Adjust authorization logic as needed
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
            'full_name' => 'required|string|max:100',
            'passport_number' => 'required|string|max:20',
            'institution' => 'nullable|string|max:100',
            'specific_institution' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'entry_datetime' => 'nullable|date',
            'exit_datetime' => 'nullable|date|after_or_equal:entry_datetime',
            'purpose_of_usage' => 'nullable|string|max:50',
            'purpose_description' => 'nullable|string',
            'equipment_used' => 'nullable|string|max:50',
            'type_of_analysis' => 'nullable|string|max:100',
            'supervisor_name' => 'nullable|string|max:100',
            'usage_duration' => 'nullable|numeric|between:0,999.99',
            'suggestions' => 'nullable|string',
            'technical_issues' => 'nullable|string'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required.',
            'passport_number.required' => 'Passport number is required.',
            'email.email' => 'Please enter a valid email address.',
            'exit_datetime.after_or_equal' => 'Exit datetime must be after or equal to entry datetime.'
        ];
    }
}