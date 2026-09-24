<?php

namespace App\Services;

use App\Models\TaiKhoan;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthChallengeService
{
    public function __construct(private OtpDelivery $delivery) {}

    public function issue(TaiKhoan $user, string $purpose, string $channel): string
    {
        $destination = $user->thongTinTaiKhoan?->{$channel === 'email' ? 'email' : 'so_dien_thoai'};
        if (! $destination) {
            throw ValidationException::withMessages(['channel' => 'Tài khoản chưa có thông tin liên hệ này.']);
        }
        $id = (string) Str::uuid();
        $code = (string) random_int(100000, 999999);
        DB::transaction(function () use ($user, $id, $code, $purpose, $channel, $destination): void {
            TaiKhoan::lockForUpdate()->findOrFail($user->id);
            DB::table('auth_challenges')->where('tai_khoan_id', $user->id)->delete();
            DB::table('auth_challenges')->insert([
                'id' => $id, 'tai_khoan_id' => $user->id, 'purpose' => $purpose,
                'channel' => $channel, 'destination' => $destination,
                'code_hash' => $channel === 'email' ? Hash::make($code) : null,
                'created_at' => now(), 'expires_at' => now()->addMinutes(10),
            ]);
        });
        try {
            $sid = $this->delivery->send($channel, $destination, $code, $purpose);
            if ($sid) {
                DB::table('auth_challenges')->where('id', $id)->update(['provider_sid' => $sid]);
            }
        } catch (\Throwable $exception) {
            DB::table('auth_challenges')->where('id', $id)->delete();
            throw $exception;
        }

        return $id;
    }

    public function consume(string $id, string $code, string $purpose, ?int $owner, Closure $success): void
    {
        $valid = DB::transaction(function () use ($id, $code, $purpose, $owner, $success): bool {
            $candidate = DB::table('auth_challenges')->where('id', $id)->first();
            if (! $candidate) {
                return false;
            }
            $user = TaiKhoan::lockForUpdate()->find($candidate->tai_khoan_id);
            $challenge = DB::table('auth_challenges')->where('id', $id)->lockForUpdate()->first();
            if (! $user || ! $challenge || $user->trang_thai !== 'hoat_dong'
                || $challenge->purpose !== $purpose || ($owner !== null && $user->id !== $owner)
                || $challenge->attempts >= 5 || now()->gte($challenge->expires_at)) {
                return false;
            }
            $field = $challenge->channel === 'email' ? 'email' : 'so_dien_thoai';
            $verified = $challenge->channel === 'email' ? 'email_verified_at' : 'phone_verified_at';
            if ($user->thongTinTaiKhoan?->{$field} !== $challenge->destination
                || ($purpose === 'reset' && ! $user->{$verified})) {
                return false;
            }
            $ok = $challenge->channel === 'email'
                ? Hash::check($code, $challenge->code_hash)
                : ($challenge->provider_sid && $this->delivery->check($challenge->provider_sid, $code));
            if (! $ok) {
                DB::table('auth_challenges')->where('id', $id)->increment('attempts');

                return false;
            }
            $success($user, $verified);
            DB::table('auth_challenges')->where('tai_khoan_id', $user->id)->delete();

            return true;
        });
        if (! $valid) {
            throw ValidationException::withMessages(['code' => 'Mã không hợp lệ, đã hết hạn hoặc đã vượt số lần thử.']);
        }
    }
}
