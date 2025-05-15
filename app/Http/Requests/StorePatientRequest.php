<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'phone:INTERNATIONAL,MOBILE,LANDLINE,NP'],
            'patient_history_file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'], 
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number format is invalid.',
            'phone.phone' => 'Phone number is invalid.', 
            'patient_history_file.mimes' => 'Only PDF files are allowed.',
        ];
    }
}
