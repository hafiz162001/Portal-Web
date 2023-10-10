<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SyaratKetentuan;

class SyaratKetentuanController extends Controller
{
    public function index(){
        $success = true;
        $data = [];
        $msg = 'Ok';
        try {
            $syaratKetentuan = SyaratKetentuan::where('page_isguest', 0)->where('category', 'blocx')->first();
            if ($syaratKetentuan) {
                $data = [
                    'name' => $syaratKetentuan->page_nm,
                    'title' => $syaratKetentuan->page_title,
                    'content' => $syaratKetentuan->page_content,
                ];
            }
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'num_of_column' => 5
        ];
        return $res;
    }

    public function indexV2(Request $request){
        $success = true;
        $data = [];
        $msg = 'Ok';
        try {
            $syaratKetentuan = SyaratKetentuan::where('page_isguest', 0)->where('category', 'evoria')->first();
            if ($syaratKetentuan) {
                $data = [
                    'name' => $syaratKetentuan->page_nm,
                    'title' => $syaratKetentuan->page_title,
                    'content' => $syaratKetentuan->page_content,
                ];
            }
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'num_of_column' => 5
        ];
        return $res;
    }
}
