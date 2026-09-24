<?php

namespace App\Http\Controllers\Api;

use App\Actions\TaiKhoan\DangKyTaiKhoan;
use App\Http\Controllers\Controller;
use App\Http\Requests\DangKyRequest;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DangKyController extends Controller
{
    public function store(
        DangKyRequest $request,
        DangKyTaiKhoan $dangKyTaiKhoan
    ): JsonResponse {
        try {
            $taiKhoan = $dangKyTaiKhoan->handle($request->validated());
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'tai_khoan' => 'Tài khoản, email hoặc số điện thoại đã được đăng ký.',
            ]);
        }

        return response()->json([
            'message' => 'Đăng ký tài khoản thành công',
            'data' => $taiKhoan->load('thongTinTaiKhoan'),
        ], 201);
    }
}
