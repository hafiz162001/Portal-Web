<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        try {
            $locationId = $request->location_id;
            if(empty($locationId)) throw new \Exception("Invalid Parameter");
            $data = Product::with(['location', 'galleries'])->where('location_id', $locationId)->orderBy('name')->paginate(10);
            $data = ProductResource::collection($data);
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $data->groupBy('productCategory.name'),
            'message' => $msg,
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];
        return $res;
    }

    public function find($id){
        $success = true;
        $data = null;
        $msg = null;
        try {
            $data = Product::with(['location', 'galleries'])->find($id);
            if($data){
                $data = new ProductResource($data);;
            }
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
