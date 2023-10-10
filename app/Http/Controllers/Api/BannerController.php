<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Http\Resources\BannerResource;
class BannerController extends Controller
{
    public function index(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        $category = 'blocx';
        $type = 'blocx';
        try {
            if (isset($request['type'])) {
                $type = $request['type'];
            }

            $banner = Banner::where([ ['category', '=', $category], ['type', '=', $type] ])->orderBy('order')->take(5)->get();
            $data = BannerResource::collection($banner);
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $res;
    }
}
