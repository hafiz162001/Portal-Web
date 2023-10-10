<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Http\Resources\WishlistResource;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    public function index(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        try {
            $wishlist = Wishlist::with(['user', 'event'])->where('user_id', auth()->user()->id)->paginate(10);
            $data = WishlistResource::collection($wishlist);
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

    public function store(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        try {
            Validator::make($request->all(), [
                'event_id' => 'required',
            ])->validate();
            $data = Wishlist::create([
                'event_id' => $request->event_id, 
                'user_id' => auth()->user()->id,
            ]);
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => new WishlistResource($data),
            'message' => $msg,
        ];
        return $res;
    }
}
