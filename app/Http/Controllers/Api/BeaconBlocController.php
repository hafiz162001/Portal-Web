<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BeaconBloc;
class BeaconBlocController extends Controller
{
    public function index(){
        $success = true;
        $data = [];
        $msg = null;
        try {
            $data = BeaconBloc::with('blocLocation')->get();
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];
        return $res;
    }

    public function find(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        try {
            $data = BeaconBloc::with('blocLocation')->where('beacon_uid', $request->beacon_uid)->first();
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];
        return $res;
    }
}
