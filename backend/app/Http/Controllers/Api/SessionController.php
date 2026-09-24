<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate(['tai_khoan' => ['required', 'string', 'max:255'], 'mat_khau' => ['required', 'string', 'max:255']]);
        $login = strtolower(trim($data['tai_khoan']));
        $user = TaiKhoan::where('tai_khoan', $login)->first();
        $valid = Hash::check($data['mat_khau'], $user?->mat_khau
            ?? '$2y$12$7fvp.MahWQdxoA07bD23Yupiwn/zYYigWo15KRBymA6vaX6Oy23xm');
        if (! $valid || ! $user || $user->trang_thai !== 'hoat_dong') {
            throw ValidationException::withMessages(['tai_khoan' => 'Tài khoản hoặc mật khẩu không đúng, hoặc tài khoản không khả dụng.']);
        }
        Auth::guard('web')->login($user);
        $request->session()->regenerate();
        $request->session()->put('auth_version', $user->auth_version);

        return response()->json(['data' => $user->load('thongTinTaiKhoan')]);
    }

    public function show(Request $request): JsonResponse
    {
        return response()->json(['data' => $request->user()->load('thongTinTaiKhoan')]);
    }

    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Đã đăng xuất.']);
    }
}
