<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavoriteTenant;
use App\Http\Resources\FavoriteTenantResource;
class FavoriteTenantController extends Controller
{
    public function popular(Request $request)
    {
        $success = true;
        $data = null;
        $msg = null;
        try {
            $data = FavoriteTenant::groupBy('location_id')->selectRaw('max(id) as id, location_id, max(created_at) as created_at, count(1) as cntr')->orderByDesc('cntr')->with(['location', 'location.blocLocation', 'location.galleries']);
            if (isset($request->bloc_location_id) && $request->bloc_location_id != null) {
                $bloc_location_id = $request->bloc_location_id;
                $data = $data->whereHas('location', function($q) use($bloc_location_id){
                    $q->where('bloc_location_id', $bloc_location_id);
                });
            }

            $data = $data->get();
            if($data){
                $data = FavoriteTenantResource::collection($data);
            }
            $data = $data->groupBy('location.name')->take(7);
            // $data = $data->groupBy('location.name')->sortByDesc(function ($product, $key) {
            //     return count($product);
            // })->take(7);
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
            $data = FavoriteTenant::where('user_id', auth()->user()->id)->latest()->paginate(10);
            if($data){
                $data = FavoriteTenantResource::collection($data);
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
                'location_id' => 'required|exists:locations,id'
            ]);
            $oldFav = FavoriteTenant::where('user_id', auth()->user()->id)->where('location_id', $request->location_id)->latest()->first();
            if($oldFav){
                return $this->dislike($request);
            }
            FavoriteTenant::create([
                'user_id' => auth()->user()->id,
                'location_id' => $request->location_id,
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
                'location_id' => 'required|exists:locations,id',
                'location_id' => 'required|exists:favorite_tenants,location_id',
            ]);
            $fav = FavoriteTenant::where('user_id', auth()->user()->id)->where('location_id', $request->location_id)->latest()->first();
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
