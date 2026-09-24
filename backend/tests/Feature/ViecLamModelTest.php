<?php

namespace Tests\Feature;

use App\Models\NhiemVuNho;
use App\Models\TaiKhoan;
use App\Models\ViecLam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViecLamModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_tai_khoan_has_many_viec_lams(): void
    {
        $taiKhoan = TaiKhoan::factory()->create();
        ViecLam::factory()->count(2)->for($taiKhoan, 'taiKhoan')->create();

        $this->assertCount(2, $taiKhoan->viecLams);
        $this->assertTrue($taiKhoan->is($taiKhoan->viecLams->first()->taiKhoan));
    }

    public function test_deleting_tai_khoan_cascades_to_its_viec_lams(): void
    {
        $taiKhoan = TaiKhoan::factory()->create();
        $viecLam = ViecLam::factory()->for($taiKhoan, 'taiKhoan')->create();

        $taiKhoan->delete();

        $this->assertDatabaseMissing('viec_lams', ['id' => $viecLam->id]);
    }

    public function test_viec_lam_casts_dates_and_supports_soft_deletes(): void
    {
        $viecLam = ViecLam::factory()->hoanThanh()->create([
            'han_chot' => '2026-09-30 18:00:00',
        ]);

        $this->assertSame('2026-09-30 18:00:00', $viecLam->han_chot->format('Y-m-d H:i:s'));
        $this->assertNotNull($viecLam->hoan_thanh_luc);

        $viecLam->delete();

        $this->assertSoftDeleted($viecLam);
    }

    public function test_viec_lam_has_ordered_nhiem_vu_nhos(): void
    {
        $viecLam = ViecLam::factory()->create(['muc_do_hoan_thanh' => 50]);
        $second = NhiemVuNho::factory()->for($viecLam, 'viecLam')->create(['thu_tu' => 2]);
        $first = NhiemVuNho::factory()->hoanThanh()->for($viecLam, 'viecLam')->create(['thu_tu' => 1]);

        $this->assertSame(50, $viecLam->muc_do_hoan_thanh);
        $this->assertTrue($first->da_hoan_thanh);
        $this->assertTrue($viecLam->is($first->viecLam));
        $this->assertTrue($viecLam->nhiemVuNhos->first()->is($first));
        $this->assertTrue($viecLam->nhiemVuNhos->last()->is($second));
    }

    public function test_deleting_viec_lam_cascades_to_nhiem_vu_nhos(): void
    {
        $viecLam = ViecLam::factory()->create();
        $nhiemVuNho = NhiemVuNho::factory()->for($viecLam, 'viecLam')->create();

        $viecLam->forceDelete();

        $this->assertDatabaseMissing('nhiem_vu_nhos', ['id' => $nhiemVuNho->id]);
    }
}
