<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email',
            'phone' => ['required', 'digits:10'],
            'address' => 'required|string|min:5|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Моля, въведете име.',
            'email.email' => 'Моля, въведете валиден имейл.',
            'phone.required' => 'Телефонът е задължителен.',
            'address.required' => 'Адресът е задължителен.',
        ];
    }
}
