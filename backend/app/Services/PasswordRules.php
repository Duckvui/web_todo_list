<?php

namespace App\Services;

class PasswordRules
{
    public static function rules(): array
    {
        return ['bail', 'required', 'string', 'min:8', 'max:20', 'regex:/\\A\\p{Lu}(?=[\\s\\S]*[\\p{P}\\p{S}])/u', 'confirmed'];
    }

    public static function messages(): array
    {
        return [
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'mat_khau.max' => 'Mật khẩu không được vượt quá 20 ký tự.',
            'mat_khau.regex' => 'Mật khẩu phải bắt đầu bằng chữ hoa và có ký tự đặc biệt.',
            'mat_khau.confirmed' => 'Mật khẩu nhập lại không khớp.',
        ];
    }
}
