<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventTicket;
use App\Http\Resources\EventResource;
use App\Http\Resources\TicketResource;

class EventController extends Controller
{
    public function index(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        $perPage = 10;

        try {
            $event = Event::with('tickets');

            if (!empty($request->type) && $request->type == "highlight") {
                $event = $event->whereDate('date_to', '>=', date('Y-m-d'))->orderBy('order', 'asc')->orderBy('date_from', 'asc');
            }else {
                $event = $event->orderBy('date_to', 'desc');
                // $event = $event->orderBy('order', 'asc')->orderBy('date_to', 'desc');
            }

            if(!empty($request->q)){
                $event->where('title', 'like', "%" . $request->q . "%");
            }

            if(!empty($request->isFeatured)){
                $event->where('isFeatured', true);
            }

            if(isset($request->bloc_location_id) && $request->bloc_location_id != null){
                $event->where('bloc_id', $request->bloc_location_id);
            }

            if (isset($request->category)) {
                $event->where('category', $request->category);
            }else {
                $event->where('category', 'blocx');
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $event = $event->paginate($perPage)->appends($request->query());

            $data = EventResource::collection($event);
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

    public function find($id){
        $success = true;
        $data = null;
        $msg = null;
        try {
            $event = Event::with('tickets')->find($id);
            if($event){
                $data = new EventResource($event);
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

    public function ticket($eventId){
        $success = true;
        $data = null;
        $msg = null;
        try {
            $event = EventTicket::with('event')->orderBy('id', 'desc')->where('event_id', $eventId);
            if (isset($request->bloc_location_id) && $request->bloc_location_id != null) {
                $event = $event->where('bloc_id', $request->bloc_location_id);
            }
            $even = $event->paginate(5);
            if($event){
                $data = TicketResource::collection($event);
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
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];
        return $res;
    }
}
