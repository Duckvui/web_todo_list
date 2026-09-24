<?php

namespace App\Http\Controllers\Api;

use App\Actions\TaiKhoan\GetThongTinTaiKhoan;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetTaiKhoanController extends Controller
{
    public function show(request $request, GetThongTinTaiKhoan $getThongTinTaiKhoan): JsonResponse
    {
        $taiKhoanId = $request ->User()->id;
        $taiKhoan = $getThongTinTaiKhoan->handle($taiKhoanId);
        if($taiKhoan ===null){
            return response()->json([
                'message' => 'Không tìm thấy thông tin tài khoản',
            ], 404);
        }
        return response()->json([
                'message' => 'Thông tin tài khoản',
                'data' => $taiKhoan,
            ], 200);
        }
}
