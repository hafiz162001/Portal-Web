<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Logger;

use App\Http\Resources\LocationResource;

class LocationController extends Controller
{
    public function index(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        try {
            Logger::create([
                'name' => 'EATS',
                'log'  => $request
            ]);
            $blocId = $request->bloc_location_id;
            $locationId = $request->location_id;
            $type = $request->type;
            $location = Location::with(['blocLocation', 'galleries']);
            if(!empty($blocId)){
                $location = $location->where('bloc_location_id', $blocId);
            }else {
                $location = $location->where('bloc_location_id', 1);
            }

            if(!empty($locationId)){
                $location = $location->where('id', $locationId);
            }

            $location = $location->orderBy('name');
            if(!empty($type)){
                $location = $location->where('type', $type);
            }

            if(!empty($request->category)){
                $location = $location->where('category', $request->category);
            }else {
                $location = $location->where('category', 'blocx');
            }

            if(!empty($request->isRecommended)){
                $location = $location->where('isRecommended', true);
            }

            if (isset($request->bloc_location_id) && $request->bloc_location_id != null) {
                $location = $location->where('bloc_location_id', $request->bloc_location_id);
            }

            $location = $location->paginate(10);
            $data = LocationResource::collection($location);
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
}
