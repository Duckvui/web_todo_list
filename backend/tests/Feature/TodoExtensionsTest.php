<?php

namespace Tests\Feature;

use App\Models\TaiKhoan;
use App\Models\ViecLam;
use Database\Seeders\TodoDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoExtensionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_todo_data_seeder_creates_related_sample_data(): void
    {
        $this->seed(TodoDataSeeder::class);

        $this->assertDatabaseCount('tai_khoans', 2);
        $this->assertDatabaseCount('danh_mucs', 2);
        $this->assertDatabaseCount('viec_lams', 5);
        $this->assertDatabaseCount('nhiem_vu_nhos', 2);
        $this->assertDatabaseCount('nhan_cong_viecs', 3);
        $this->assertDatabaseCount('nhan_viec_lam', 3);
        $this->assertDatabaseCount('loi_nhacs', 1);
        $this->assertDatabaseCount('tep_dinh_kems', 1);
        $this->assertDatabaseCount('lich_su_viec_lams', 1);
        $this->assertDatabaseCount('chia_se_viec_lams', 1);
    }

    public function test_extended_todo_relationships_are_available(): void
    {
        $this->seed(TodoDataSeeder::class);

        $chuSoHuu = TaiKhoan::query()->where('tai_khoan', 'todo@example.com')->firstOrFail();
        $nguoiDuocChiaSe = TaiKhoan::query()->where('tai_khoan', 'congtac@example.com')->firstOrFail();
        $viecLam = ViecLam::query()->where('tieu_de', 'Hoàn thành báo cáo tuần')->firstOrFail();

        $this->assertCount(3, $chuSoHuu->nhanCongViecs);
        $this->assertCount(2, $viecLam->nhanCongViecs);
        $this->assertCount(1, $viecLam->loiNhacs);
        $this->assertCount(1, $viecLam->tepDinhKems);
        $this->assertCount(1, $viecLam->lichSus);
        $this->assertCount(1, $viecLam->chiaSes);
        $this->assertTrue($nguoiDuocChiaSe->viecLamsDuocChiaSe->first()->is($viecLam));
        $this->assertSame('chinh_sua', $nguoiDuocChiaSe->viecLamsDuocChiaSe->first()->pivot->quyen);
    }

    public function test_deleting_viec_lam_cascades_to_all_dependent_records(): void
    {
        $this->seed(TodoDataSeeder::class);
        $viecLam = ViecLam::query()->where('tieu_de', 'Hoàn thành báo cáo tuần')->firstOrFail();

        $viecLam->forceDelete();

        $this->assertDatabaseCount('nhiem_vu_nhos', 0);
        $this->assertDatabaseCount('nhan_viec_lam', 1);
        $this->assertDatabaseCount('loi_nhacs', 0);
        $this->assertDatabaseCount('tep_dinh_kems', 0);
        $this->assertDatabaseCount('lich_su_viec_lams', 0);
        $this->assertDatabaseCount('chia_se_viec_lams', 0);
    }
}
