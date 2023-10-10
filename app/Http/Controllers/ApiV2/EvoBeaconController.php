<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserActivityResource;
use App\Models\Beacon;
use App\Models\Ticket;
use App\Models\BeaconActivity;
use App\Models\UserApps;
use App\Models\UserActivity;
use App\Models\Logger;

class EvoBeaconController extends Controller
{
    public function beaconActivitiesOld(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        $beaconType = null;
        $dataIdxBeacon = [];
        $dataBeacon = [];
        $ourIdxBeacon = [];
        $ourBeacon = [];
        $promo= [];
        $dataGuesId = 0;

        Logger::create([
            'name' => 'EVO_BEACON',
            'log'  => $request,
        ]);

        try {
            $dataArray = array_values(Arr::sort($request->all(), function ($value) {
                return $value['range'];
            }));
            $firstBeacon = null;
            $idx = 0;
            foreach ($dataArray as $key => $value) {
                if(!empty($value['beacon_uid'])){
                    $dataIdxBeacon[$value['beacon_uid']]=$idx;
                    $dataBeacon[]=$value['beacon_uid'];
                    // $dataGuesId = $value['gues_id'];
                }
                $idx++;
            }
            if(count($dataBeacon)> 0 ) {
                Logger::create([
                    'name' => 'EVO_BEACON_DATA',
                    'log'  => $request,
                ]);
                 $ourBeacon = Beacon::select(['beacon_uid','beacon_type_id'])->where('beacon_status',1)->whereIn('beacon_uid', $dataBeacon)->orWhereIn('beacon_ios_uid', $dataBeacon)->get();
                 if($ourBeacon) {
                    foreach($ourBeacon as $obv){
                        if(array_key_exists($obv['beacon_uid'], $dataIdxBeacon)) {
                             if(auth()->user()->isCheckin){
                                if($obv['beacon_type_id']!=2) {
                                    $ourIdxBeacon[$dataIdxBeacon[$obv['beacon_uid']]]=$obv['beacon_uid'];
                                }
                             }
                             else{
                                $ourIdxBeacon[$dataIdxBeacon[$obv['beacon_uid']]]=$obv['beacon_uid'];
                             }
                        }
                    }
                 }
                ksort($ourIdxBeacon);
            }
            if(count($ourIdxBeacon)>0) {
                $ourFirstBeacon = current($ourIdxBeacon);
                $beacon = Beacon::with(['beaconType'])->where('beacon_uid',$ourFirstBeacon)->orWhere('beacon_ios_uid',$ourFirstBeacon)->first();

                 if($beacon){
                        if(!auth()->user()->isCheckin){
                            $beacon = Beacon::with(['beaconType'])->where('beacon_type_id',2)->where('beacon_location',$beacon->beacon_location)->first();
                        }
                        $beaconActivity = BeaconActivity::create([
                            'beacon_id' => $beacon->id
                        ]);
                        $firstBeacon = $beacon;
                    }
            }
            if(!empty($firstBeacon)){
                Logger::create([
                    'name' => 'EVO_BEACON_FIRST',
                    'log'  => $request,
                ]);
                if(!auth()->user()->isCheckin){
                    $newRequest = new \Illuminate\Http\Request($firstBeacon->toArray());
                    //add gues id to new request
                    // $newRequest->merge(['gues_id' => $dataGuesId]);
                    return self::checkIn($newRequest);
                }else{
                    $newRequest = new \Illuminate\Http\Request($firstBeacon->toArray());
                    switch ($firstBeacon->beacon_type_id) {
                        case 3: //'checkout'
                            // $newRequest->merge(['gues_id' => $dataGuesId]);
                            return self::checkOut($newRequest);
                            break;

                        case 1: //'art'
                            return self::art($newRequest);
                            break;

                        case 5: //'merch'
                            return self::merch($newRequest);
                            break;

                        case 6: //'event'
                            $eventBody = collect(Arr::where($request->all(), function ($value, $key) use($newRequest){
                                return $value["beacon_uid"] == $newRequest->beacon_uid;
                            }))->first();
                            if(!empty($eventBody) && !empty($eventBody["isDetailPage"])){
                                $newRequest->merge(['ticket_id' => $eventBody["ticket_id"]]);
                                return self::event($newRequest);
                            }
                            break;

                        case 7: //event checkout
                            return self::eventCheckOut($newRequest);
                            break;

                        default:
                            # code...
                            break;
                    }
                }
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
            'beaconType'=> $beaconType,
            'promo'=> $promo
        ];

        return $res;
    }

    public function beaconActivities(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        $beaconType = null;
        $dataIdxBeacon = [];
        $dataBeacon = [];
        $ourIdxBeacon = [];
        $ourBeacon = [];
        $promo= [];
        $dataGuesId = 0;

        // Logger::create([
        //     'name' => 'EVO_BEACON',
        //     'log'  => $request,
        // ]);

        try {
            $dataArray = array_values(Arr::sort($request->all(), function ($value) {
                return $value['range'];
            }));

            $counter = count($dataArray);

            if ($counter > 0) {
                for ($i=0; $i < $counter; $i++) {
                    $ourBeacon = Beacon::select(['id', 'beacon_uid','beacon_type_id', 'range'])->where('beacon_status',1)->where('beacon_uid', $dataArray[$i]['beacon_uid'])->orWhere('beacon_ios_uid', $dataArray[$i]['beacon_uid'])->first();
                    if ($ourBeacon) {
                        if (($dataArray[$i]['range'] * 100) <= $ourBeacon->range) {
                            if(!auth()->user()->isCheckin){
                                $ticket = Ticket::join('ticket_users', 'tickets.id', '=', 'ticket_users.ticket_id')->where('tickets.checkin_beacon_id', '=', $ourBeacon->id)->whereIn('ticket_users.status', [1,3])->where('ticket_users.user_apps_id', '=', auth()->user()->id)->first();

                                if ($ticket) {
                                    $res = [
                                        'success'    => $success,
                                        'data'       => $ticket,
                                        'message'    => $msg,
                                        'is_checkin' => false,
                                    ];

                                    return $res;
                                }
                            }else{
                                $ticket = Ticket::join('ticket_users', 'tickets.id', '=', 'ticket_users.ticket_id')->where('tickets.checkout_beacon_id', '=', $ourBeacon->id)->where('ticket_users.status', '=', 2)->where('ticket_users.user_apps_id', '=', auth()->user()->id)->first();

                                if ($ticket) {
                                    $res = [
                                        'success'    => $success,
                                        'data'       => $ticket,
                                        'message'    => $msg,
                                        'is_checkin' => true,
                                    ];

                                    return $res;
                                }
                            }
                        }
                    }
                }
            }

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }
    }

    public function checkIn(Request $request)
    {
        $success = true;
        $data = [];
        $msg = "Anda telah memasuki area M Bloc";
        $beaconType = null;
        $promo= [];
        try {
            Validator::make($request->all(), [
                'beacon_uid' => 'required',
            ])->validate();

            //Check yang login gues atau bukan
            if (isset($request->gues_id)) {
                $gues = DB::table('gues_fcm_token')->where([ ['id', '=', $request->gues_id], ['is_chekin', '=', true] ])->first();
                if ($gues) {
                    throw new \Exception("User has already checked in");
                }
            }else {//user yang udah login
                if(auth()->user()->isCheckin){
                    throw new \Exception("User has already checked in");
                }
            }
            if(auth()->user()->role == 0){
                throw new \Exception("User does not have access");
            }
            $beacon = Beacon::with('beaconType')->where('beacon_uid', $request->beacon_uid)->orWhere('beacon_ios_uid', $request->beacon_uid)->where('beacon_type_id', 2)->first();
            if(!$beacon){
                throw new \Exception("Beacon not found");
            }
            $beaconType = $beacon->beaconType;
            // $beacon->beaconBlocRelation->blocLocation ini perlu di cek

            //checkin gues
            if (isset($request->gues_id)) {
                $userActivity = UserActivity::create([
                    'checkin_beacon_id' => $beacon->id,
                    'bloc_location_id' => $beacon->beaconBlocRelation->blocLocation->id,
                    'user_id' => auth()->user()->id,
                    'checkin_at' => \Carbon\Carbon::now(),
                    // 'gues_id' => $request->gues_id,
                ]);

                if($userActivity){
                    auth()->user()->update([
                        'isCheckin' => true
                    ]);

                    // DB::table('gues_fcm_token')->where([ ['id', '=', $request->gues_id] ])->update([
                    //     'is_chekin' => true
                    // ]);
                }
            }else {//chekin user yang udah login
                $userActivity = UserActivity::create([
                    'checkin_beacon_id' => $beacon->id,
                    'bloc_location_id' => $beacon->beaconBlocRelation->blocLocation->id,
                    'user_id' => auth()->user()->id,
                    'checkin_at' => \Carbon\Carbon::now(),
                ]);
                if($userActivity){
                    auth()->user()->update([
                        'isCheckin' => true
                    ]);
                }
            }
            $data = new UserActivityResource($userActivity);
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'beaconType' => $beaconType,
            'promo'=> $promo
        ];
        return $res;
    }

    public function checkOut(Request $request)
    {
        $success = true;
        $data = [];
        $msg = "Anda telah meninggalkan area M Bloc";
        $beaconType = null;
        $promo= [];
        try {
            if (isset($request->gues_id)) {
                $gues = DB::table('gues_fcm_token')->where([ ['id', '=', $request->gues_id], ['is_chekin', '=', false] ])->first();
                if ($gues) {
                    throw new \Exception("User has already checked out");
                }
            }else {//user yang udah login
                if(!auth()->user()->isCheckin){
                    throw new \Exception("User has already checked out");
                }
            }

            if(auth()->user()->role == 0){
                throw new \Exception("User does not have access");
            }
            $beaconUid = $request->beacon_uid;
            $beaconId = null;
            if(!empty($beaconUid)){
                $beacon = Beacon::with('beaconType')->where('beacon_uid', $request->beacon_uid)->orWhere('beacon_ios_uid', $request->beacon_uid)->where('beacon_type_id', 3)->first();
                if(!$beacon){
                    throw new \Exception("Beacon not found");
                }
                $beaconId = $beacon->id;
                $beaconType = $beacon->beaconType;
            }
            $userActivity = UserActivity::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->first();

            //checkout guess
            if (isset($request->gues_id)) {
                $userActivity->update([
                    'checkout_beacon_id' => $beaconId,
                    'user_id' => auth()->user()->id,
                    'checkout_at' => \Carbon\Carbon::now(),
                    'gues_id' => $request->gues_id,
                ]);

                if($userActivity){
                    auth()->user()->update([
                        'isCheckin' => false
                    ]);

                    DB::table('gues_fcm_token')->where([ ['id', '=', $request->gues_id] ])->update([
                        'is_chekin' => false
                    ]);
                }
            }else {
                $userActivity->update([
                    'checkout_beacon_id' => $beaconId,
                    'user_id' => auth()->user()->id,
                    'checkout_at' => \Carbon\Carbon::now(),
                ]);

                if($userActivity){
                    auth()->user()->update([
                        'isCheckin' => false
                    ]);
                }
            }

            $data = new UserActivityResource($userActivity);
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'beaconType' => $beaconType,
            'promo'=> $promo
        ];
        return $res;
    }

}
