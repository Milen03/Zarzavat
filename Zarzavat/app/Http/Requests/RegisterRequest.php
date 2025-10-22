<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages(): array{

        return[
            'name.required' => 'Моля, въведи име.',
            'email.required' => 'Имейлът е задължителен.',
            'email.unique' => 'Този имейл вече е регистриран.',
            'password.min' => 'Паролата трябва да е поне 6 символа.',
            'password.confirmed' => 'Паролите не съвпадат.',
        ];
    }
}
