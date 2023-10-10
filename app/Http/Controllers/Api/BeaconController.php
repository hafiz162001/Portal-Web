<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beacon;
class BeaconController extends Controller
{
    public function index(){
        $success = true;
        $data = [];
        $msg = null;
        try {
            $data = Beacon::with('beaconType')->get();
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

    public function find(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        try {
            $data = Beacon::with('beaconType')->where('beacon_uid', $request->beacon_uid)->first();
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

    public function findX(Request $request){
        $success = true;
        $data = [];
        $msg = null;

        //get data beacon
        $beaconUuid = $request->beacon_uuid;
        $beaconUuidIos = $request->beacon_uuid_ios;
        $beaconManu = $request->beacon_manu;
        //insert with update if match with beacon_manu

        if($beaconManu) {
            if($beaconUuid){
                $sql = "insert into beacons (beacon_uid,beacon_ios_uid,beacon_manu, name,range,beacon_type_id) 
                values ('{$beaconUuid}','{$beaconUuidIos}','{$beaconManu}','AREA-UNKNOWN',5000,4) 
                on conflict (beacon_uid) do update set beacon_manu = '{$beaconManu}'  ";
            }
            if($beaconUuidIos){
                // $sql = "insert into beacons (beacon_uid,beacon_ios_uid,beacon_manu, name,range,beacon_type_id) 
                // values ('{$beaconUuid}','{$beaconUuidIos}','{$beaconManu}','AREA-UNKNOWN',5000,4) 
                // on conflict (beacon_manu) do update set beacon_ios_uid = '{$beaconUuidIos}' ";
                $sql = " update beacons set beacon_ios_uid = '{$beaconUuidIos}' where  beacon_manu = '{$beaconManu}' ";
            }
        \DB::statement($sql);
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];
        return $res;  
    }
}
