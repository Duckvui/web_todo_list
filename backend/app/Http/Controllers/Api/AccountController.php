<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use App\Services\PasswordRules;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'ho_ten' => ['sometimes', 'required', 'string', 'max:255'],
            'ngay_sinh' => ['sometimes', 'nullable', 'date_format:Y-m-d', 'before_or_equal:today'],
            'gioi_tinh' => ['sometimes', 'nullable', 'in:nam,nu,khac'],
            'email' => ['prohibited'], 'so_dien_thoai' => ['prohibited'],
            'tai_khoan' => ['prohibited'], 'trang_thai' => ['prohibited'],
        ]);
        $request->user()->thongTinTaiKhoan()->firstOrFail()->update($data);

        return response()->json(['data' => $request->user()->fresh()->load('thongTinTaiKhoan')]);
    }

    public function password(Request $request): JsonResponse
    {
        $data = $request->validate([
            'mat_khau_hien_tai' => ['required', 'string', 'max:255'],
            'mat_khau' => PasswordRules::rules(),
        ], PasswordRules::messages());
        DB::transaction(function () use ($request, $data): void {
            $user = TaiKhoan::lockForUpdate()->findOrFail($request->user()->id);
            if (! Hash::check($data['mat_khau_hien_tai'], $user->mat_khau)) {
                throw ValidationException::withMessages(['mat_khau_hien_tai' => 'Mật khẩu hiện tại không đúng.']);
            }
            $user->forceFill(['mat_khau' => Hash::make($data['mat_khau']),
                'auth_version' => $user->auth_version + 1, 'remember_token' => null])->save();
            DB::table('auth_challenges')->where('tai_khoan_id', $user->id)->delete();
        });
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Đã đổi mật khẩu và đăng xuất các phiên. Vui lòng đăng nhập lại.']);
    }
}
