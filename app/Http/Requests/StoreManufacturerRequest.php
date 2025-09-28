<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreManufacturerRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255|unique:manufacturers,name',
            'url' => 'nullable|url|string|max:255',
            'support_url' => 'nullable|url|string|max:255',
            'support_phone' => 'nullable|string|max:20',
            'support_email' => 'nullable|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [ 'name.unique' => 'A manufacturer with this name already exists.' ];
    }
}
