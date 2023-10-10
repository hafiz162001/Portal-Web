<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TicketOrder;
use App\Models\TicketOrderLog;
use App\Models\EatsOrder;
use App\Models\Logger;
use App\Models\Order;
use App\Models\TicketUser;
use App\Models\UserApps;
use App\Models\Voucher;
use App\Http\Resources\TicketOrderResource;

use App\Mail\myMailer;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $success = true;
        $data = [];
        $msg = null;
        $paymentResponse = null;
        try {
            if($request["product-desc"] == "ticket"){
                $ticketOrder = TicketOrder::find($request->id);
            }
            $paymentResponse = payToMidtrans($request->all());
            if(!empty($paymentResponse)){
                if(!empty($paymentResponse["data"]) && !empty($paymentResponse["data"]["invoice-id"])){
                    $ticketOrder->update([
                        'invoice_id' => $paymentResponse["data"]["invoice-id"]
                    ]);
                }
            }
        } catch (\Throwable $th) {
            $success = false;
            $msg = config('app.error_message');
        }
        $res = [
            'request' => $request->all(),
            'success' => $success,
            'data'    => new TicketOrderResource($ticketOrder),
            'message' => $msg,
            'paymentResponse' => $paymentResponse,
        ];
        return $res;
    }

    public function notification(Request $request)
    {
        $success = true;
        $data = null;
        $msg = null;
        $is_order_ticket = false;
        $is_order_evoria_ticket = false;

        try {
            $data = EatsOrder::where('invoice_number', $request['invoice-id'])->first();

            if (!$data) {
                $data = TicketOrder::where('invoice_id', $request['invoice-id'])->latest()->first();
                $is_order_ticket = true;

                if (!$data) {
                    $data = Order::where('invoice_number', $request['invoice-id'])->latest()->first();
                    $is_order_evoria_ticket = true;
                }
            }

            if(empty($data)){
                throw new \Exception("Data not found");
            }

            if(!empty($request['status-payment'])){
                if($request['status-payment'] == "PAID"){
                    $voucher = Voucher::where('voucher_code', $data->voucher_code)->where('status', 1)->first();
                    if ($voucher) {
                        $voucher->update([
                            'redeemed_date' => date('Y-m-d'),
                            'user_apps_id' => $data->user_apps_id,
                            'status' => 3
                        ]);
                    }
                    $data->update([
                        'status' => 1
                    ]);

                    //if evoria ticket payment success
                    if($is_order_evoria_ticket){
                        $link = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=";
                        $email = '';
                        $otp = [];

                        $evoria_ticket_user = TicketUser::where('invoice_number', $request['invoice-id']);

                        $res_update = $evoria_ticket_user->update([
                            'status' => 1
                        ]);

                        $res = $evoria_ticket_user->get();

                        foreach ($res as $key => $val) {
                            $email = $val->customer_email;
                            $otp[] = $link.md5($val->ticket_code);
                        }

                        $details = [
                            'title' => 'Evoria',
                            'desc' => 'Your Qr Code',
                            'otp' => $otp,
                            'email' => $email,
                        ];

                        $response = Mail::to($details['email'])->send(new \App\Mail\myMailer($details));

                    }

                }else{
                    $data->update([
                        'status' => 3
                    ]);
                }
            }

            if($is_order_ticket){
                TicketOrderLog::create([
                    'ticket_order_id' => $data->id,
                    'response' => $request->all(),
                ]);
            }
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'request' => $request->all()
        ];
        \Log::info($res);
        return $res;
    }

    public function mbmPaymentNotification(Request $request)
    {
        Logger::create([
            'name' => 'MBM_CALLBACK',
            'log'  => json_encode($request)
        ]);

        $callBackToken = $request->header('x-callback-token');
        $isOk = false;
        $orderStatus = 0;

        if($request){
            if(isset($callBackToken) && $callBackToken == Xendit::$apiTokenCallback ) {
                if( isset($request['method']) && $request ){
                    if( $request['method'] == "virtual_account" ){
                        $data['invoice_number'] = $request["external_id"];
                        $orderStatus = 1;
                        $isOk = true;
                    }
                    if( $request['method'] == "ewallets" ){
                        if(isset($request['data']) ) {
                            if($request['data']['status']=="SUCCEEDED") {
                                $data['invoice_number'] = $request['data']['reference_id'];
                                $orderStatus = 1;
                                $isOk = true;
                            }
                        }
                    }
                    if( $request['method'] == "credit_card" ){

                    }
                    if( $request['method'] == "retail" ){

                    }
                    if( $request['method'] == "paylater" ){

                    }
                    if( $request['method'] == "disbursement" ){
                        if(isset($request['data']) ) {
                            if($request['data']['status']=="COMPLETED") {
                                $data['invoice_number'] = $request['data']["external_id"];
                                $orderStatus = 1;
                                $isOk = true;
                            }
                        }
                    }
                }
                if($isOk){
                    $order = null;
                    $orderId = 0;
                    $orderType = "";
                    $isOrderSchema = false;

                    $order = Order::where('invoice_number', $data['invoice_number'])->first();
                    $isOrderSchema = true;

                    if($isOrderSchema && $order) {

                        // self::logger([$order->invoice_number,$order->order_type,$data],"XENDIT_NOTIFY_ORDER_2",$order->pid);
                        $arr = [];
                        // $order->order_response = json_encode($request);
                        $order->status = $orderStatus;
                        $order->payment_date = date("Y-m-d H:i:s");
                        $arr[]='status';
                        $arr[]='payment_date';
                        if(count($arr) > 0){
                            $order->update($arr);
                            // return $order;
                            if($order->status == 1){
                                if($order->order_type == 'ticket') {//ticket

                                }
                                if($order->order_type == 'mbloc-market') {//mbm

                                }

                                return "CONFIRMED";
                            }else if($order->status == 5){
                                return "FAILED";
                            }else{
                                return "PENDING";
                            }
                        }else{
                            return "WRONG PARAM ORDER";
                        }
                    }
                }else{
                    return "FAILED NOT OK";
                }
            }else{
                return "FAILED WRONG CALLBACK";
            }

        }else{
            return "FAILED NO PAYLOAD";
        }
    }
}
