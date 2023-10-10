<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavoriteProduct;
use App\Http\Resources\FavoriteProductResource;
use App\Models\Product;

class FavoriteProductController extends Controller
{
    public function popular(Request $request)
    {
        $success = true;
        $data = null;
        $msg = null;
        try {
            $data = FavoriteProduct::with(['user', 'location'])->get();
            if($data){
                $data = FavoriteProductResource::collection($data);
            }
            $data = $data->groupBy('location.name')->sortByDesc(function ($product, $key) {
                return count($product);
            })->take(7);
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

    public function index(Request $request)
    {
        $success = true;
        $data = null;
        $msg = null;
        try {
            $data = FavoriteProduct::where('user_id', auth()->user()->id)->latest()->paginate(10);
            if($data){
                $data = FavoriteProductResource::collection($data);
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
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];
        return $res;
    }

    public function like(Request $request)
    {
        $success = true;
        $data = null;
        $msg = null;
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id'
            ]);
            $product = Product::find($request->product_id);
            $oldFav = FavoriteProduct::where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->latest()->first();
            if($oldFav){
                return $this->dislike($request);
            }
            FavoriteProduct::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->product_id,
                'location_id' => $product->location_id,
            ]);
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
    
    public function dislike(Request $request)
    {
        $success = true;
        $data = null;
        $msg = null;
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'product_id' => 'required|exists:favorite_products,product_id',
            ]);
            $fav = FavoriteProduct::where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->latest()->first();
            if($fav){
                $fav->delete();
            }
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];
        return $res;
    }
}
