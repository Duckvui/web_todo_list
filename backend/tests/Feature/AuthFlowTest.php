<?php

namespace Tests\Feature;

use App\Models\TaiKhoan;
use App\Services\OtpDelivery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    private function registration(array $changes = []): array
    {
        return array_replace(['tai_khoan' => 'test.user@gmail.com', 'mat_khau' => 'Password!1',
            'mat_khau_confirmation' => 'Password!1', 'ho_ten' => 'Nguyễn Văn Đức'], $changes);
    }

    public function test_registration_creates_linked_profile_and_hides_password(): void
    {
        $response = $this->postJson('/api/dang-ky', $this->registration());
        $response->assertCreated()->assertJsonPath('data.thong_tin_tai_khoan.email', 'test.user@gmail.com')
            ->assertJsonMissingPath('data.mat_khau')->assertJsonMissingPath('data.auth_version');
        $this->assertTrue(Hash::check('Password!1', TaiKhoan::first()->mat_khau));
        $this->assertDatabaseCount('tai_khoans', 1);
        $this->assertDatabaseCount('thong_tin_tai_khoans', 1);
        $this->postJson('/api/dang-ky', $this->registration())->assertUnprocessable();
        $this->assertDatabaseCount('tai_khoans', 1);
    }

    public function test_phone_registration_does_not_require_email(): void
    {
        $this->postJson('/api/dang-ky', $this->registration(['tai_khoan' => '0912345678']))
            ->assertCreated()->assertJsonPath('data.thong_tin_tai_khoan.so_dien_thoai', '0912345678')
            ->assertJsonPath('data.thong_tin_tai_khoan.email', null);
    }

    public function test_invalid_account_password_and_server_fields_are_rejected(): void
    {
        $this->postJson('/api/dang-ky', $this->registration([
            'tai_khoan' => 'bad@@.gmail.com', 'mat_khau' => 'lowercase',
            'mat_khau_confirmation' => 'lowercase', 'trang_thai' => 'hoat_dong',
        ]))->assertUnprocessable()->assertJsonValidationErrors(['tai_khoan', 'mat_khau', 'trang_thai']);
        $this->assertDatabaseCount('tai_khoans', 0);
    }

    public function test_login_me_logout_and_locked_account(): void
    {
        $user = TaiKhoan::factory()->create();
        $this->getJson('/api/me')->assertUnauthorized();
        $this->postJson('/api/dang-nhap', ['tai_khoan' => $user->tai_khoan, 'mat_khau' => 'Wrong!123'])->assertUnprocessable();
        $this->login($user);
        $this->getJson('/api/me')->assertOk()->assertJsonPath('data.id', $user->id);
        $this->postJson('/api/dang-xuat')->assertOk();
        $this->getJson('/api/me')->assertUnauthorized();
        $user->update(['trang_thai' => 'bi_khoa']);
        $this->postJson('/api/dang-nhap', ['tai_khoan' => $user->tai_khoan, 'mat_khau' => 'Password!1'])->assertUnprocessable();
    }

    public function test_login_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/dang-nhap', ['tai_khoan' => 'missing@gmail.com', 'mat_khau' => 'Wrong!123'])->assertUnprocessable();
        }
        $this->postJson('/api/dang-nhap', ['tai_khoan' => 'missing@gmail.com', 'mat_khau' => 'Wrong!123'])->assertStatus(429);
    }

    public function test_profile_and_password_change_revoke_sessions(): void
    {
        $user = TaiKhoan::factory()->create();
        $this->login($user);
        $this->patchJson('/api/thong-tin-ca-nhan', ['ho_ten' => 'Tên mới'])->assertOk();
        $this->patchJson('/api/thong-tin-ca-nhan', ['email' => 'another@gmail.com'])->assertUnprocessable();
        $this->putJson('/api/doi-mat-khau', ['mat_khau_hien_tai' => 'Password!1',
            'mat_khau' => 'Newpass!2', 'mat_khau_confirmation' => 'Newpass!2'])->assertOk();
        $this->assertTrue(Hash::check('Newpass!2', $user->fresh()->mat_khau));
        $this->assertSame(1, $user->fresh()->auth_version);
        $this->getJson('/api/me')->assertUnauthorized();
        $this->actingAs($user->fresh(), 'web')->withSession(['auth_version' => 0])->getJson('/api/me')->assertUnauthorized();
    }

    public function test_email_otp_verification_is_single_use_and_required_for_recovery(): void
    {
        $user = TaiKhoan::factory()->create();
        $sent = [];
        $delivery = \Mockery::mock(OtpDelivery::class);
        $delivery->shouldReceive('available')->andReturn(true);
        $delivery->shouldReceive('send')->andReturnUsing(function ($channel, $destination, $code, $purpose) use (&$sent) {
            $sent[] = compact('channel', 'destination', 'code', 'purpose');

            return null;
        });
        $this->app->instance(OtpDelivery::class, $delivery);
        $this->login($user);
        $id = $this->postJson('/api/xac-minh/gui-ma', ['channel' => 'email'])->assertOk()->json('challenge_id');
        $this->postJson('/api/xac-minh/kiem-tra', ['challenge_id' => $id, 'code' => $sent[0]['code']])->assertOk();
        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->postJson('/api/xac-minh/kiem-tra', ['challenge_id' => $id, 'code' => $sent[0]['code']])->assertUnprocessable();
        $this->postJson('/api/dang-xuat')->assertOk();
        $this->travel(61)->seconds();
        $id = $this->postJson('/api/quen-mat-khau', ['tai_khoan' => $user->tai_khoan, 'channel' => 'email'])
            ->assertOk()->json('challenge_id');
        $this->postJson('/api/dat-lai-mat-khau', ['challenge_id' => $id, 'code' => $sent[1]['code'],
            'mat_khau' => 'Resetpass!2', 'mat_khau_confirmation' => 'Resetpass!2'])->assertOk();
        $this->assertTrue(Hash::check('Resetpass!2', $user->fresh()->mat_khau));
        $this->assertSame(1, $user->fresh()->auth_version);
        $this->postJson('/api/dat-lai-mat-khau', ['challenge_id' => $id, 'code' => $sent[1]['code'],
            'mat_khau' => 'Resetpass!3', 'mat_khau_confirmation' => 'Resetpass!3'])->assertUnprocessable();
    }

    public function test_unknown_and_unverified_recovery_have_same_response_shape(): void
    {
        $user = TaiKhoan::factory()->create();
        $delivery = \Mockery::mock(OtpDelivery::class);
        $delivery->shouldReceive('available')->andReturn(true);
        $delivery->shouldNotReceive('send');
        $this->app->instance(OtpDelivery::class, $delivery);
        $first = $this->postJson('/api/quen-mat-khau', ['tai_khoan' => $user->tai_khoan, 'channel' => 'email'])->assertOk();
        $other = $this->postJson('/api/quen-mat-khau', ['tai_khoan' => 'missing@gmail.com', 'channel' => 'email'])->assertOk();
        $this->assertSame($first->json('message'), $other->json('message'));
        $this->assertTrue(Str::isUuid($other->json('challenge_id')));
        $this->assertDatabaseCount('auth_challenges', 0);
    }

    public function test_wrong_expired_and_wrong_purpose_codes_are_rejected(): void
    {
        $user = TaiKhoan::factory()->create();
        $this->login($user);
        $id = $this->challenge($user);
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/xac-minh/kiem-tra', ['challenge_id' => $id, 'code' => '000000'])->assertUnprocessable();
        }
        $this->postJson('/api/xac-minh/kiem-tra', ['challenge_id' => $id, 'code' => '123456'])->assertUnprocessable();
        $this->assertSame(5, DB::table('auth_challenges')->where('id', $id)->value('attempts'));
        $id = $this->challenge($user, ['expires_at' => now()->subSecond()]);
        $this->postJson('/api/xac-minh/kiem-tra', ['challenge_id' => $id, 'code' => '123456'])->assertUnprocessable();
        $id = $this->challenge($user);
        $this->postJson('/api/dat-lai-mat-khau', ['challenge_id' => $id, 'code' => '123456',
            'mat_khau' => 'Resetpass!2', 'mat_khau_confirmation' => 'Resetpass!2'])->assertUnprocessable();
    }

    public function test_otp_is_bound_to_its_owner(): void
    {
        $owner = TaiKhoan::factory()->create();
        $other = TaiKhoan::factory()->create();
        $id = $this->challenge($owner);
        $this->login($other);
        $this->postJson('/api/xac-minh/kiem-tra', ['challenge_id' => $id, 'code' => '123456'])->assertUnprocessable();
        $this->assertNull($owner->fresh()->email_verified_at);
    }

    public function test_sms_uses_provider_verification_and_marks_phone_verified(): void
    {
        $user = TaiKhoan::factory()->create();
        $user->thongTinTaiKhoan->update(['so_dien_thoai' => '0912345678']);
        $delivery = \Mockery::mock(OtpDelivery::class);
        $delivery->shouldReceive('available')->with('sms')->andReturn(true);
        $delivery->shouldReceive('send')->with('sms', '0912345678', \Mockery::type('string'), 'verify')->once()->andReturn('VE_test');
        $delivery->shouldReceive('check')->with('VE_test', '123456')->once()->andReturn(true);
        $this->app->instance(OtpDelivery::class, $delivery);
        $this->login($user);
        $id = $this->postJson('/api/xac-minh/gui-ma', ['channel' => 'sms'])->assertOk()->json('challenge_id');
        $this->postJson('/api/xac-minh/kiem-tra', ['challenge_id' => $id, 'code' => '123456'])->assertOk();
        $this->assertNotNull($user->fresh()->phone_verified_at);
    }

    public function test_csrf_protection_rejects_missing_token(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        $this->postJson('/api/dang-ky', $this->registration())->assertStatus(419);
    }

    public function test_database_session_is_stored_on_server(): void
    {
        config(['session.driver' => 'database']);
        $this->app->forgetInstance('session');
        $this->app->forgetInstance('session.store');
        $user = TaiKhoan::factory()->create();
        $this->login($user);
        $this->assertDatabaseHas('sessions', ['user_id' => $user->id]);
    }

    public function test_missing_sms_configuration_is_reported(): void
    {
        config(['services.twilio.sid' => null]);
        $user = TaiKhoan::factory()->create();
        $this->login($user);
        $this->postJson('/api/xac-minh/gui-ma', ['channel' => 'sms'])->assertStatus(503);
    }

    public function test_delivery_failure_removes_pending_challenge(): void
    {
        $user = TaiKhoan::factory()->create();
        $delivery = \Mockery::mock(OtpDelivery::class);
        $delivery->shouldReceive('available')->andReturn(true);
        $delivery->shouldReceive('send')->andThrow(new \RuntimeException('Provider unavailable'));
        $this->app->instance(OtpDelivery::class, $delivery);
        $this->login($user);
        $this->postJson('/api/xac-minh/gui-ma', ['channel' => 'email'])->assertStatus(503);
        $this->assertDatabaseCount('auth_challenges', 0);
    }

    private function login(TaiKhoan $user): void
    {
        Auth::forgetGuards();
        $this->postJson('/api/dang-nhap', ['tai_khoan' => $user->tai_khoan, 'mat_khau' => 'Password!1'])->assertOk();
    }

    private function challenge(TaiKhoan $user, array $changes = []): string
    {
        $id = (string) Str::uuid();
        DB::table('auth_challenges')->insert(array_replace([
            'id' => $id, 'tai_khoan_id' => $user->id, 'purpose' => 'verify', 'channel' => 'email',
            'destination' => $user->thongTinTaiKhoan->email, 'code_hash' => Hash::make('123456'),
            'created_at' => now(), 'expires_at' => now()->addMinutes(10),
        ], $changes));

        return $id;
    }
}
