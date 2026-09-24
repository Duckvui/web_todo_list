<?php

namespace Database\Seeders;

use App\Models\ChiaSeViecLam;
use App\Models\DanhMuc;
use App\Models\LichSuViecLam;
use App\Models\LoiNhac;
use App\Models\NhanCongViec;
use App\Models\NhiemVuNho;
use App\Models\TaiKhoan;
use App\Models\TepDinhKem;
use App\Models\ViecLam;
use Illuminate\Database\Seeder;

class TodoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chuSoHuu = TaiKhoan::factory()->create(['tai_khoan' => 'todo@example.com']);
        $nguoiDuocChiaSe = TaiKhoan::factory()->create(['tai_khoan' => 'congtac@example.com']);

        $congViec = DanhMuc::factory()->for($chuSoHuu, 'taiKhoan')->create([
            'ten' => 'Công việc',
            'mau_sac' => '#2563EB',
        ]);
        $caNhan = DanhMuc::factory()->for($chuSoHuu, 'taiKhoan')->create([
            'ten' => 'Cá nhân',
            'mau_sac' => '#16A34A',
        ]);

        $nhanGap = NhanCongViec::factory()->for($chuSoHuu, 'taiKhoan')->create([
            'ten' => 'Gấp',
            'mau_sac' => '#DC2626',
        ]);
        $nhanQuanTrong = NhanCongViec::factory()->for($chuSoHuu, 'taiKhoan')->create([
            'ten' => 'Quan trọng',
            'mau_sac' => '#F59E0B',
        ]);
        $nhanTaiNha = NhanCongViec::factory()->for($chuSoHuu, 'taiKhoan')->create([
            'ten' => 'Ở nhà',
            'mau_sac' => '#7C3AED',
        ]);

        $baoCao = ViecLam::factory()->for($chuSoHuu, 'taiKhoan')->for($congViec, 'danhMuc')->create([
            'tieu_de' => 'Hoàn thành báo cáo tuần',
            'mo_ta' => 'Tổng hợp tiến độ và các vấn đề cần xử lý.',
            'trang_thai' => 'dang_lam',
            'muc_do_hoan_thanh' => 50,
            'muc_do_uu_tien' => 'cao',
            'han_chot' => now()->addDays(2),
        ]);
        $baoCao->nhanCongViecs()->attach([$nhanGap->id, $nhanQuanTrong->id]);

        NhiemVuNho::factory()->for($baoCao, 'viecLam')->hoanThanh()->create([
            'tieu_de' => 'Thu thập số liệu',
            'thu_tu' => 1,
        ]);
        NhiemVuNho::factory()->for($baoCao, 'viecLam')->create([
            'tieu_de' => 'Viết phần tổng kết',
            'thu_tu' => 2,
        ]);
        LoiNhac::factory()->for($baoCao, 'viecLam')->create([
            'thoi_gian_nhac' => now()->addDay(),
            'noi_dung' => 'Báo cáo tuần sắp đến hạn.',
        ]);
        TepDinhKem::factory()->for($baoCao, 'viecLam')->create([
            'ten_goc' => 'mau-bao-cao.pdf',
            'duong_dan' => 'tep-dinh-kem/mau-bao-cao.pdf',
        ]);
        LichSuViecLam::factory()->for($baoCao, 'viecLam')->create([
            'nguoi_thuc_hien_id' => $chuSoHuu->id,
        ]);
        ChiaSeViecLam::factory()->for($baoCao, 'viecLam')->for($nguoiDuocChiaSe, 'taiKhoan')->create([
            'quyen' => 'chinh_sua',
            'trang_thai' => 'da_chap_nhan',
            'chap_nhan_luc' => now(),
        ]);

        $muaSam = ViecLam::factory()->for($chuSoHuu, 'taiKhoan')->for($caNhan, 'danhMuc')->create([
            'tieu_de' => 'Mua đồ dùng trong tuần',
            'muc_do_uu_tien' => 'trung_binh',
            'han_chot' => now()->addDays(4),
        ]);
        $muaSam->nhanCongViecs()->attach($nhanTaiNha);

        ViecLam::factory()->count(3)->for($chuSoHuu, 'taiKhoan')->create();
    }
}
