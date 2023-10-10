<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promo;
use App\Http\Resources\PromoResource;
class PromoController extends Controller
{
    public function index(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        try {
            $promo = Promo::with('codes')->orderBy('order');
            if(!empty($request->q)){
                $promo->where('title', 'like', "%" . $request->q . "%");
            }
            $promo = $promo->paginate(1);
            $data = PromoResource::collection($promo);
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];
        return $res;
    }
}
