<?php

namespace App\Http\Controllers\ApiV2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppsMenu;
use App\Http\Resources\AppsMenuResource;

class AppsMenuController extends Controller
{
    public function index(){
        $success = true;
        $data = [];
        $msg = null;
        try {
            $data = AppsMenuResource::collection(AppsMenu::where([ ['category', '=', 'evoria'] ])->orderBy('order')->get());
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'num_of_column' => 3
        ];
        return $res;
    }
}
