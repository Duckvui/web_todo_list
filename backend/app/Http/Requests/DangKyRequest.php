<?php

namespace App\Http\Requests;

use App\Services\PasswordRules;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class DangKyRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $account = $this->input('tai_khoan');
        if (is_string($account)) {
            $account = strtolower(trim($account));
            $this->merge(['tai_khoan' => $account]);
            if (str_contains($account, '@') && ! $this->filled('email')) {
                $this->merge(['email' => $account]);
            } elseif (preg_match('/\A[0-9]{10}\z/', $account) === 1 && ! $this->filled('so_dien_thoai')) {
                $this->merge(['so_dien_thoai' => $account]);
            }
        }
        if (is_string($this->input('email'))) {
            $this->merge(['email' => strtolower(trim($this->input('email')))]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string|Closure>>
     */
    public function rules(): array
    {
        $rules = [
            'tai_khoan' => [
                'bail',
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (preg_match('/\A[0-9]{10}\z/', $value) === 1) {
                        return;
                    }

                    if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false
                        && substr_count($value, '@') === 1
                        && str_ends_with(strtolower($value), '@gmail.com')) {
                        return;
                    }

                    $fail('Tài khoản phải là địa chỉ Gmail hợp lệ có đuôi @gmail.com hoặc số điện thoại gồm đúng 10 chữ số.');
                },
                'unique:tai_khoans,tai_khoan',
            ],
            'mat_khau' => PasswordRules::rules(),
            'mat_khau_confirmation' => ['required', 'string'],
            'ho_ten' => ['bail', 'required', 'string', 'max:255'],
            'ngay_sinh' => ['bail', 'nullable', 'date_format:Y-m-d', 'before_or_equal:today'],
            'gioi_tinh' => ['bail', 'nullable', 'string', 'in:nam,nu,khac'],
            'email' => [
                'bail',
                'nullable',
                'string',
                'max:255',
                'email:rfc',
                'regex:/\A[^@\s]+@gmail\.com\z/i',
                'unique:thong_tin_tai_khoans,email',
            ],
            'so_dien_thoai' => [
                'bail',
                'nullable',
                'string',
                'regex:/\A[0-9]{10}\z/',
                'unique:thong_tin_tai_khoans,so_dien_thoai',
            ],
            'id' => ['prohibited'],
            'tai_khoan_id' => ['prohibited'],
            'trang_thai' => ['prohibited'],
            'created_at' => ['prohibited'],
            'updated_at' => ['prohibited'],
        ];

        $taiKhoan = $this->input('tai_khoan');

        if (is_string($taiKhoan)) {
            if (str_contains($taiKhoan, '@')) {
                $rules['email'][] = 'required';
                $rules['email'][] = 'same:tai_khoan';
            } elseif (preg_match('/\A[0-9]{10}\z/', $taiKhoan) === 1) {
                $rules['so_dien_thoai'][] = 'required';
                $rules['so_dien_thoai'][] = 'same:tai_khoan';
            }
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tai_khoan.required' => 'Vui lòng nhập Gmail hoặc số điện thoại.',
            'tai_khoan.string' => 'Tài khoản phải là chuỗi ký tự.',
            'tai_khoan.max' => 'Tài khoản không được vượt quá 255 ký tự.',
            'tai_khoan.unique' => 'Gmail hoặc số điện thoại này đã được đăng ký.',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
            'mat_khau.string' => 'Mật khẩu phải là chuỗi ký tự.',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'mat_khau.max' => 'Mật khẩu không được vượt quá 20 ký tự.',
            'mat_khau.regex' => 'Mật khẩu phải bắt đầu bằng chữ hoa và chứa ít nhất một ký tự đặc biệt.',
            'mat_khau.confirmed' => 'Mật khẩu nhập lại không khớp.',
            'mat_khau_confirmation.required' => 'Vui lòng nhập lại mật khẩu.',
            'mat_khau_confirmation.string' => 'Mật khẩu nhập lại phải là chuỗi ký tự.',
            'ho_ten.required' => 'Vui lòng nhập họ tên.',
            'ho_ten.string' => 'Họ tên phải là chuỗi ký tự.',
            'ho_ten.max' => 'Họ tên không được vượt quá 255 ký tự.',
            'ngay_sinh.date_format' => 'Ngày sinh phải là ngày hợp lệ theo định dạng YYYY-MM-DD.',
            'ngay_sinh.before_or_equal' => 'Ngày sinh không được ở tương lai.',
            'gioi_tinh.string' => 'Giới tính phải là chuỗi ký tự.',
            'gioi_tinh.in' => 'Giới tính phải là nam, nu hoặc khac.',
            'email.required' => 'Vui lòng nhập địa chỉ Gmail.',
            'email.string' => 'Email phải là chuỗi ký tự.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.email' => 'Email không đúng định dạng.',
            'email.regex' => 'Email phải có đúng đuôi @gmail.com.',
            'email.unique' => 'Email này đã được đăng ký.',
            'email.same' => 'Email phải trùng với tài khoản đăng ký bằng Gmail.',
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại đã dùng làm tài khoản.',
            'so_dien_thoai.string' => 'Số điện thoại phải được gửi dưới dạng chuỗi để giữ số 0 ở đầu.',
            'so_dien_thoai.regex' => 'Số điện thoại phải gồm đúng 10 chữ số.',
            'so_dien_thoai.unique' => 'Số điện thoại này đã được đăng ký.',
            'so_dien_thoai.same' => 'Số điện thoại phải trùng với tài khoản đăng ký bằng số điện thoại.',
            'id.prohibited' => 'ID do hệ thống tự tạo.',
            'tai_khoan_id.prohibited' => 'Liên kết tài khoản do hệ thống tự tạo.',
            'trang_thai.prohibited' => 'Trạng thái tài khoản do hệ thống quản lý.',
            'created_at.prohibited' => 'Thời gian tạo do hệ thống tự ghi nhận.',
            'updated_at.prohibited' => 'Thời gian cập nhật do hệ thống tự ghi nhận.',
        ];
    }
}
