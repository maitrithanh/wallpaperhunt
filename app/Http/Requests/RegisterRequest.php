<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'name' => trim($this->name),
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
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],

            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:users,email'
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng điền tên',
            'name.string' => 'Tên phải là một chuỗi',
            'name.min' => 'Tên phải có ít nhất 3 ký tự',
            'name.max' => 'Tên không được vượt quá 255 ký tự',
            'name.regex' => 'Tên chỉ được chứa chữ cái, số và khoảng trắng',

            'email.required' => 'Vui lòng điền Email',
            'email.email' => 'Email không hợp lệ',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'email.unique' => 'Email đã tồn tại',

            'password.required' => 'Vui lòng điền mật khẩu',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.letters' => 'Mật khẩu phải chứa ít nhất một chữ cái',
            'password.mixedCase' => 'Mật khẩu phải chứa cả chữ hoa và chữ thường',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một số',
            'password.symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt',
        ];
    }
}
