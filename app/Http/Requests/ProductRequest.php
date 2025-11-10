<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Името на продукта е задължително.',
            'price.required' => 'Цената е задължителна.',
            'image.image' => 'Каченият файл трябва да е изображение.',
            'category_id.required' => 'Категорията е задължителна.',
            'category_id.exists' => 'Невалидна категория.',
        ];
    }
}
