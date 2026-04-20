<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // sanitize input
        $this->merge([
            'email' => strtolower(trim($this->email)),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
            ],
            'password' => [
                'required',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Vui lòng điền Email',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng điền mật khẩu',
        ];
    }
}
