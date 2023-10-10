<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlocLocation;
use App\Http\Resources\BlocLocationResource;
class BlocLocationController extends Controller
{
    public function index(){
        $success = true;
        $data = [];
        $msg = null;
        try {
            $block = BlocLocation::with('village')->orderBy('name')->get();
            $data = BlocLocationResource::collection($block);
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
