<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Xendit\Xendit;
use Illuminate\Support\Facades\Validator;
use \Carbon\Carbon;
use App\Models\Xendit as Payment;
use Illuminate\Support\Facades\Log;

class XenditController extends Controller
{

    public function __construct()
    {
        Xendit::setApiKey(config('app.xenditToken'));
    }

    public function getListVa()
    {
        $success = true;
        $data = [];
        $msg = null;
        try {
            $data = \Xendit\VirtualAccounts::getVABanks();
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

    public function createVa(Request $request)
    {
        $success = true;
        $data = null;
        $msg = null;
        $params = null;
        try {

            Validator::make($request->all(), [
                'bank' => 'required',
                'email' => 'required',
                'price' => 'required',
            ])->validate();

            $externalId = "va-" . time();
            $params = [
                "external_id" => $externalId,
                "bank_code" => $request->bank,
                "name" => $request->email,
                "expected_amount" => $request->price,
                "is_closed" => true,
                "expiration_date" => Carbon::now()->addDays(1)->toISOString(),
                "is_single_use" => true
            ];
            $body = [
                "payment_channel" => "Virtual Account",
                "external_id" => $externalId,
                "request" => json_encode($params)
            ];
            $body = array_merge($body, $request->all());
            $createData = Payment::create($body);
            $data = \Xendit\VirtualAccounts::create($params);
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'request' => $params,
        ];
        return $res;
    }

    public function callbackVa(Request $request)
    {
        $success = true;
        $data = NULL;
        $msg = null;
        Log::info($request->all());
        try {
            $externalId = $request->external_id;
            $status = $request->status;
            $data = Payment::where("external_id", $externalId)->first();
            if(!empty($data)){
                if($status == "ACTIVE") {
                    $data->status = 1;
                }
                $data->update();
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
            'request' => $request->all()
        ];
        return $res;
    }
}
