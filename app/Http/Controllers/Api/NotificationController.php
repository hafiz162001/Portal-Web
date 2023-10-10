<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Validator;
class NotificationController extends Controller
{
    public function index(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        $type = $request->type;

        try {
            $data = Notification::where('user_apps_id', auth()->user()->id)->orWhere('user_apps_id', null)->orderBy('id', 'desc');
            if(!empty($type)){
                $data->where('type', $type);
            }
            $data = $data->paginate(10);
            $data = NotificationResource::collection($data);
            $count = count($data);

            $update = Notification::where('user_apps_id', auth()->user()->id)->update(['status' => 2]);
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'count'   => $count,
            'message' => $msg,
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];
        return $res;
    }

    public function detail($id){
        $success = true;
        $data = null;
        $msg = null;
        try {
            $notif = Notification::find($id);

            if ($notif) {
                $notif->status = 2;
                $notif->save();
            }

            $data = new NotificationResource($notif);
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
