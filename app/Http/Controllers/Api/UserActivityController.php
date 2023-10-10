<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserApps;
use App\Models\UserActivity;
use App\Models\Beacon;
use App\Models\Promo;
use App\Models\BeaconActivity;
use App\Http\Resources\UserActivityResource;
use App\Http\Resources\BeaconArtResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\TicketOrderResource;
use App\Http\Resources\TicketResource;
use App\Models\BeaconRelation;
use App\Models\TicketOrder;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Logger;
use App\Models\TicketUser;
use Illuminate\Support\Facades\Validator;
use \Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use Kutia\Larafirebase\Facades\Larafirebase;

class UserActivityController extends Controller
{
    public function scan(Request $request){
        return self::userActivity($request);
    }

    public function petugasScan(Request $request)
    {
        $success = true;
        $data = [];
        $msg = null;
        try {
            Logger::create([
                'name' => 'PETUGAS_SCAN',
                'log'  => $request,
            ]);
            Validator::make($request->all(), [
                'code' => 'required',
            ])->validate();

            $explodedCode = explode("~!@#", $request->code);
            $orderId = null;
            $userId = null;
            if(count($explodedCode) == 2){
                $explodedOrder = explode("=",$explodedCode[0]);
                if(count($explodedOrder) == 2){
                    if(Hash::check('order_id', $explodedOrder[0])){
                        $orderId = $explodedOrder[1];
                    }
                }

                $explodedUser = explode("=",$explodedCode[1]);
                $userId = $explodedUser;
                if(count($explodedUser) == 2){
                    if(Hash::check('user_id', $explodedUser[0])){
                        $userId = $explodedUser[1];
                    }
                }

                $hasTicket = TicketOrder::with(['eventTicket', 'eventTicket.event', 'user'])
                ->where(DB::raw('md5(id::text)') , $orderId)
                ->where(DB::raw('md5(user_id::text)') , $userId)
                ->where('status', 1)
                ->whereDate('selected_date', Carbon::today())
                ->first();
                //  update ticket status to is_used
                if($hasTicket){
                    $hasTicket->update([
                        'isUsed' => true
                    ]);
                    $hasTicket->user->update([
                        'active_event_id' => $hasTicket->eventTicket->event_id,
                        'active_ticket_order_id' => $hasTicket->id
                    ]);
                    $data = new TicketOrderResource($hasTicket);
                }else{
                    throw new \Exception("Ticket not valid, please check your ticket");
                }
            }else {
                $ticket_user = TicketUser::where('status', '!=', 0)
                ->where(DB::raw('md5(ticket_code::text)') , $request->code)->first();

                if ($ticket_user) {
                    switch ($ticket_user->status) {
                        case '1':
                            $ticket_user->status = 2;
                            $ticket_user->save();

                            // $ticket_user->update([
                            //     'status' => 1
                            // ]);
                            $msg = 'Berhasil Checkin';
                            break;
                        case '2':
                            $ticket_user->status = 3;
                            $ticket_user->save();
                            $msg = 'Berhasil Checkout';
                            break;

                        default:
                            $msg = 'Mohon Lakukan Pembayaran Ticket';
                            break;
                    }
                }else {
                    $success = false;
                    $msg = 'Ticket Tidak Ditemukan';
                }
            }

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'request' => $request->all(),
        ];
        return $res;
    }

    public function index(Request $request)
    {
        $success = true;
        $data = [];
        $msg = null;
        $data["totalPengunjung"] = 0;
        $active = 1; //1 or 0
        try {
            if($request->active != null){
                $active = $request->active;
            }
            $activity = UserActivity::with(['user', 'blocLocation'])->whereHas('user', function ($query) use ($active, $request) {
                $queryTmp = $query->where('role', 1)->where('isCheckin', $active == 1)->whereDate('created_at', Carbon::today());
                if(!empty($request->gender)){
                    $queryTmp = $queryTmp->where('gender', $request->gender);
                }
                if(!empty($request->name)){
                    $queryTmp = $queryTmp->where('name', 'LIKE', '%'. $request->name . '%');
                }
                return $queryTmp;
            })->where('bloc_location_id', auth()->user()->bloc_location_id)->orderBy('id', 'desc');

            $activityCount = UserActivity::with(['user', 'blocLocation'])->whereHas('user', function ($query) {
                $queryTmp = $query->where('role', 1)->where('isCheckin', true)->whereDate('created_at', Carbon::today());
                return $queryTmp;
            })->where('bloc_location_id', auth()->user()->bloc_location_id)->orderBy('id', 'desc');

            $activity = $activity->get()->unique('user_id');

            $data["totalPengunjung"] = $activityCount->get()->unique('user_id')->count();
            $data["listData"] = UserActivityResource::collection($activity);

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

    public function userActivities(Request $request){
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
        //     'name' => 'User Activities',
        //     'log'  => $request,
        // ]);

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
                    $dataGuesId = $value['gues_id'];
                }
                $idx++;
            }
            if(count($dataBeacon)> 0 ) {
                 $ourBeacon = Beacon::select(['beacon_uid','beacon_type_id'])->where('beacon_status',1)->whereIn('beacon_uid', $dataBeacon)->orWhereIn('beacon_ios_uid', $dataBeacon)->get();
                 if($ourBeacon) {
                    foreach($ourBeacon as $obv){
                        if(array_key_exists($obv['beacon_uid'], $dataIdxBeacon)) {
                             if(auth()->user()->isCheckin){
                                if($obv['beacon_type_id']!=2) {
                                    $ourIdxBeacon[$dataIdxBeacon[$obv['beacon_uid']]]=$obv['beacon_uid'];
                                }
                             }else{
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
                if(!auth()->user()->isCheckin){
                    $newRequest = new \Illuminate\Http\Request($firstBeacon->toArray());
                    //add gues id to new request
                    $newRequest->merge(['gues_id' => $dataGuesId]);
                    return self::checkIn($newRequest);
                }else{
                    $newRequest = new \Illuminate\Http\Request($firstBeacon->toArray());
                    switch ($firstBeacon->beacon_type_id) {
                        case 3: //'checkout'
                            $newRequest->merge(['gues_id' => $dataGuesId]);
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

    public function userActivitiesCheckInEvent(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        $beaconType = null;
        $dataIdxBeacon = [];
        $dataBeacon = [];
        $ourIdxBeacon = [];
        $ourBeacon = [];
        $ourCheckBeacon = [];
        $promo= [];
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
                }
                $idx++;
            }
            if(count($dataBeacon)> 0 ) {
                 $ourBeacon = Beacon::select(['beacon_uid','beacon_type_id'])->whereIn('beacon_type_id',[2,6])->where('beacon_status',1)->whereIn('beacon_uid', $dataBeacon)->orWhereIn('beacon_ios_uid', $dataBeacon)->get();
                 if($ourBeacon) {
                    foreach($ourBeacon as $obv){
                        $ourCheckBeacon[]=$obv['beacon_uid'];
                        if(array_key_exists($obv['beacon_uid'], $dataIdxBeacon)) {
                             if(auth()->user()->isCheckin){
                                if($obv['beacon_type_id']!=2) {
                                    $ourIdxBeacon[$dataIdxBeacon[$obv['beacon_uid']]]=$obv['beacon_uid'];
                                }
                             }else{
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
                if(!auth()->user()->isCheckin){
                    $newRequest = new \Illuminate\Http\Request($firstBeacon->toArray());
                    return self::checkIn($newRequest);
                }else{
                    $newRequest = new \Illuminate\Http\Request($firstBeacon->toArray());
                    switch ($firstBeacon->beacon_type_id) {
                        case 6: //'event'
                            $eventBody = collect(Arr::where($request->all(), function ($value, $key) use($newRequest){
                                return $value["beacon_uid"] == $newRequest->beacon_uid;
                            }))->first();
                            if(!empty($eventBody) && !empty($eventBody["isDetailPage"])){
                                $newRequest->merge(['ticket_id' => $eventBody["ticket_id"]]);
                                return self::event($newRequest);
                            }
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
            'promo'=> $promo,
        ];
        return $res;
    }

    public function userActivitiesOriginal(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        try {
            $dataArray = array_values(Arr::sort($request->all(), function ($value) {
                return $value['range'];
            }));
            $firstBeacon = null;
            foreach ($dataArray as $key => $value) {
                Validator::make($value, [
                    'beacon_uid' => 'required',
                    'range' => 'required',
                ])->validate();
                if(!empty($value['beacon_uid'])){
                    $beacon = Beacon::with(['beaconType'])->where('beacon_uid',$value['beacon_uid'])->orWhere('beacon_ios_uid',$value['beacon_uid'])->first();
                    if($beacon){
                        $beaconActivity = BeaconActivity::create([
                            'beacon_id' => $beacon->id
                        ]);
                        if($key == 0){
                            $firstBeacon = $beacon;
                        }
                    }
                }
            }
            if(!empty($firstBeacon)){
                $newRequest = new \Illuminate\Http\Request($firstBeacon->toArray());
                switch ($firstBeacon->beacon_type_id) {
                    case 2: //'checkin'
                        return self::checkIn($newRequest);
                        break;

                    case 3: //'checkout'
                        return self::checkOut($newRequest);
                        break;

                    case 1: //'art'
                        return self::art($newRequest);
                        break;

                    case 5: //'merch'
                        return self::merch($newRequest);
                        break;

                    case 6: //'event'
                        return self::event($newRequest);
                        break;

                    default:
                        # code...
                        break;
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
        ];
        return $res;
    }

    public function userActivity(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        try {
            Validator::make($request->all(), [
                'beacon_type' => 'required',
            ])->validate();
            if(!empty($request->beacon_uid)){
                $beacon = Beacon::where('beacon_uid',$request->beacon_uid)->orWhere('beacon_ios_uid',$request->beacon_uid)->first();
                if($beacon){
                    $beaconActivity = BeaconActivity::create([
                        'beacon_id' => $beacon->id
                    ]);
                    $request->merge(['beacon_id' => $beacon->id]);
                }
            }
            switch ($request->beacon_type) {
                case 'checkin':
                    return self::checkIn($request);
                    break;

                case 'checkout':
                    return self::checkOut($request);
                    break;

                case 'art':
                    return self::art($request);
                    break;

                case 'merch':
                    return self::merch($request);
                    break;

                case 'event':
                    return self::event($request);
                    break;

                default:
                    # code...
                    break;
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

    public function event(Request $request)
    {
        $success = true;
        $data = [];
        $msg = null;
        $beaconType = null;
        $promo= [];
        try {
            Validator::make($request->all(), [
                'beacon_type' => 'required',
                'beacon_uid' => 'required',
            ])->validate();
            $beacon = Beacon::with('beaconType')->where('beacon_uid', $request->beacon_uid)->orWhere('beacon_ios_uid', $request->beacon_uid)->where('beacon_type_id', 6)->first();

            if(!$beacon){
                throw new \Exception("Beacon not found");
            }
            $beaconType = $beacon->beaconType;
            $beaconRelations = BeaconRelation::where('beacon_id', $beacon->id)->where('parent', 'event')->get();
            $beaconRelation = null;
            foreach ($beaconRelations as $key => $beaconRel) {
                $dateFrom = $beaconRel->event->date_from;
                $dateTo = $beaconRel->event->date_to;
                $betweenDate = Carbon::today()->between($dateFrom, $dateTo);
                $timeFrom = Carbon::create($beaconRel->event->time_from);
                $timeTo = Carbon::create($beaconRel->event->time_to);
                $betweenTime = Carbon::now()->between($timeFrom, $timeTo);
                if($betweenDate && $betweenTime){
                    $beaconRelation = $beaconRel;
                }
            }
            $dateNow=date("Y-m-d");
            $promo = Promo::select(["id","title","order","image","period_of_use","usage","description","term_and_condition"])->where('beacon_id', $beacon->id)->where('date_from', '<=', $dateNow)->where('date_to', '>=', $dateNow)->get();

            if($beaconRelation){
                // check if user has ticket for this event
                $eventId = $beaconRelation->parent_id;
                $hasTicket = TicketOrder::with(['eventTicket', 'eventTicket.event'])
                ->whereHas('eventTicket', function($q) use($eventId){
                    $q->where('event_id', $eventId);
                })
                ->where('user_id', auth()->user()->id)
                ->where('status', 1)
                ->whereDate('selected_date', Carbon::today())
                ->first();

                //  update ticket status to is_used
                 if($hasTicket){
                    $hasTicket->update([
                        'isUsed' => true,
                        'ticket_used_at' => now(),
                    ]);
                    auth()->user()->update([
                        'active_event_id' => $hasTicket->eventTicket->event_id,
                        'active_ticket_order_id' => $hasTicket->id
                    ]);
                    $data = new TicketOrderResource($hasTicket);
                 }else{
                     throw new \Exception("Ticket not found");
                 }
            }else{
                throw new \Exception("Event not found");
            }
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'beaconType' => $beaconType,
            'promo' => $promo
        ];
        return $res;
    }

    public function merch(Request $request)
    {
        $success = true;
        $data = [];
        $msg = null;
        $beaconType = null;
        $promo= [];
        try {
            Validator::make($request->all(), [
                'beacon_type' => 'required',
                'beacon_uid' => 'required',
            ])->validate();
            $beacon = Beacon::with('beaconType')->where('beacon_uid', $request->beacon_uid)->orWhere('beacon_ios_uid', $request->beacon_uid)->where('beacon_type_id', 5)->first();
            if(!$beacon){
                throw new \Exception("Beacon not found");
            }
            $beaconType = $beacon->beaconType;
            $beaconRelation = BeaconRelation::where('beacon_id', $beacon->id)->where('parent', 'location')->first();
            $dateNow=date("Y-m-d");
            $promo = Promo::select(["id","location_id","title","order","image","period_of_use","usage","description","term_and_condition"])->where('beacon_id', $beacon->id)->where('date_from', '<=', $dateNow)->where('date_to', '>=', $dateNow)->get();

            if(empty($beaconRelation)){
                throw new \Exception("Merchant not found");
            }
            $data = new LocationResource($beaconRelation->location);
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

    public function art(Request $request)
    {
        $success = true;
        $data = [];
        $msg = null;
        $beaconType = null;
        $promo= [];
        try {
            Validator::make($request->all(), [
                'beacon_type' => 'required',
                'beacon_uid' => 'required',
            ])->validate();
            $beacon = Beacon::with('beaconType')->where('beacon_uid', $request->beacon_uid)->orWhere('beacon_ios_uid', $request->beacon_uid)->where('beacon_type_id', 1)->first();
            if(!$beacon){
                throw new \Exception("Beacon not found");
            }
            $beaconType = $beacon->beaconType;
            $beaconRelation = BeaconRelation::where('beacon_id', $beacon->id)->where('parent', 'beaconArt')->first();
            $dateNow=date("Y-m-d");
            $promo = Promo::select(["id","title","order","image","period_of_use","usage","description","term_and_condition"])->where('beacon_id', $beacon->id)->where('date_from', '<=', $dateNow)->where('date_to', '>=', $dateNow)->get();

            if(empty($beaconRelation)){
                throw new \Exception("Art not found");
            }
            $data = new BeaconArtResource($beaconRelation->beaconArt);
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
                    'gues_id' => $request->gues_id,
                ]);

                if($userActivity){
                    auth()->user()->update([
                        'isCheckin' => true
                    ]);

                    DB::table('gues_fcm_token')->where([ ['id', '=', $request->gues_id] ])->update([
                        'is_chekin' => true
                    ]);
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
    public function eventCheckOut(Request $request)
    {
        $success = true;
        $data = [];
        $msg = "Anda telah meninggalkan area Event";
        $beaconType = null;
        $promo= [];
        try {
            //check beacon event checkout ( base on event id )

            //checkout from event
            $event = Event::where('id', $request->event_id)->first();

            if (!$event) {
                throw new \Exception("Event not found");
            }

            $eventTicket = EventTicket::where('event_id', $event->id)->first();

            if (!$eventTicket) {
                throw new \Exception("Event Ticket not found");
            }

            $ticketOrder = TicketOrder::where('event_ticket_id', $eventTicket->id)->update([
                'isUsed' => false,
                'ticket_checkout_at' => date('Y-m-d H:i:s')
            ]);

            if (!$ticketOrder) {
                throw new \Exception("Ticket order not found");
            }

            $user = UserApps::where('id', auth()->user()->id)->update([
                'active_event_id' => null,
                'active_ticket_order_id' => null
            ]);

            $data = $ticketOrder;

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            // 'beaconType' => $beaconType,
            // 'promo'=> $promo
        ];
        return $res;
    }
}
