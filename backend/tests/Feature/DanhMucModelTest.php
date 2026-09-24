<?php

namespace Tests\Feature;

use App\Models\DanhMuc;
use App\Models\TaiKhoan;
use App\Models\ViecLam;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DanhMucModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_tai_khoan_has_many_danh_mucs_and_danh_muc_has_many_viec_lams(): void
    {
        $taiKhoan = TaiKhoan::factory()->create();
        $danhMuc = DanhMuc::factory()->for($taiKhoan, 'taiKhoan')->create();
        $viecLam = ViecLam::factory()->for($taiKhoan, 'taiKhoan')->for($danhMuc, 'danhMuc')->create();

        $this->assertTrue($taiKhoan->danhMucs->first()->is($danhMuc));
        $this->assertTrue($danhMuc->taiKhoan->is($taiKhoan));
        $this->assertTrue($danhMuc->viecLams->first()->is($viecLam));
        $this->assertTrue($viecLam->danhMuc->is($danhMuc));
    }

    public function test_deleting_danh_muc_keeps_viec_lam_without_a_category(): void
    {
        $taiKhoan = TaiKhoan::factory()->create();
        $danhMuc = DanhMuc::factory()->for($taiKhoan, 'taiKhoan')->create();
        $viecLam = ViecLam::factory()->for($taiKhoan, 'taiKhoan')->for($danhMuc, 'danhMuc')->create();

        $danhMuc->delete();

        $this->assertNull($viecLam->fresh()->danh_muc_id);
    }

    public function test_category_names_are_unique_within_each_account(): void
    {
        $taiKhoan = TaiKhoan::factory()->create();
        DanhMuc::factory()->for($taiKhoan, 'taiKhoan')->create(['ten' => 'Công việc']);

        $this->expectException(UniqueConstraintViolationException::class);

        DanhMuc::factory()->for($taiKhoan, 'taiKhoan')->create(['ten' => 'Công việc']);
    }
}
