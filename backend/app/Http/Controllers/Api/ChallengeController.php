<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use App\Services\AuthChallengeService;
use App\Services\OtpDelivery;
use App\Services\PasswordRules;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ChallengeController extends Controller
{
    public function __construct(private AuthChallengeService $challenges, private OtpDelivery $delivery) {}

    public function sendVerification(Request $request): JsonResponse
    {
        $data = $request->validate(['channel' => ['required', 'in:email,sms']]);
        abort_unless($this->delivery->available($data['channel']), 503, 'Chưa cấu hình dịch vụ gửi OTP.');
        try {
            $id = $this->challenges->issue($request->user(), 'verify', $data['channel']);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable) {
            Log::warning('Không gửi được mã xác minh.');
            abort(503, 'Chưa gửi được mã xác minh. Vui lòng thử lại sau.');
        }

        return response()->json(['challenge_id' => $id, 'message' => 'Đã gửi mã xác minh.']);
    }

    public function verify(Request $request): JsonResponse
    {
        $data = $request->validate($this->codeRules());
        $this->challenges->consume($data['challenge_id'], $data['code'], 'verify', $request->user()->id,
            function (TaiKhoan $user, string $verified): void {
                $user->forceFill([$verified => now()])->save();
            });

        return response()->json(['message' => 'Xác minh thành công.']);
    }

    public function forgot(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tai_khoan' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'in:email,sms'],
        ]);
        abort_unless($this->delivery->available($data['channel']), 503, 'Chưa cấu hình dịch vụ gửi OTP.');
        $id = (string) Str::uuid();
        $user = TaiKhoan::where('tai_khoan', strtolower(trim($data['tai_khoan'])))
            ->where('trang_thai', 'hoat_dong')->first();
        $verified = $data['channel'] === 'email' ? 'email_verified_at' : 'phone_verified_at';
        if ($user && $user->{$verified}) {
            try {
                $id = $this->challenges->issue($user, 'reset', $data['channel']);
            } catch (\Throwable) {
                Log::warning('Không gửi được yêu cầu khôi phục mật khẩu.');
            }
        }

        return response()->json([
            'challenge_id' => $id,
            'message' => 'Nếu tài khoản có kênh liên hệ đã xác minh, mã khôi phục sẽ được gửi.',
        ]);
    }

    public function reset(Request $request): JsonResponse
    {
        $data = $request->validate($this->codeRules() + ['mat_khau' => PasswordRules::rules()], PasswordRules::messages());
        $this->challenges->consume($data['challenge_id'], $data['code'], 'reset', null,
            function (TaiKhoan $user) use ($data): void {
                $user->forceFill(['mat_khau' => Hash::make($data['mat_khau']),
                    'auth_version' => $user->auth_version + 1, 'remember_token' => null])->save();
            });

        return response()->json(['message' => 'Đã đặt lại mật khẩu. Vui lòng đăng nhập lại.']);
    }

    private function codeRules(): array
    {
        return ['challenge_id' => ['required', 'uuid'], 'code' => ['required', 'string', 'regex:/\\A[0-9]{6}\\z/']];
    }
}
