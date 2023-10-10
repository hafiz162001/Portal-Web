<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\VisitorResource;
class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $success = true;
        $data = [];
        $msg = null;
        try {
            $wishlist = Visitor::with(['createdBy'])->where('created_by', auth()->user()->id)->paginate(10);
            $data = VisitorResource::collection($wishlist);
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

    public function create(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        try {
            Validator::make($request->all(), [
                'phone' => 'required',
                'email' => 'required',
                'title' => 'required',
                'name' => 'required',
                'ktp' => 'required',
            ])->validate();
            $data = Visitor::updateOrCreate([
                'created_by'   => auth()->user()->id,
                'phone'   => $request->phone,
                'email'   => $request->email,
            ],[
                'title'    => $request->title,
                'name'    => $request->name,
                'ktp'    => $request->ktp,
            ]);
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }
        $res = [
            'request' => $request->all(),
            'success' => $success,
            'data'    => new VisitorResource($data),
            'message' => $msg,
        ];
        return $res;
    }
}
