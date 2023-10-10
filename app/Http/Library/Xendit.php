<?php

namespace App\Http\Library;

use Illuminate\Http\Request;
use App\Models\Logger;

class Xendit
{
    public static $apiKey = "xnd_production_JE09svTW9rOX05LOY8mXpGsQC8gO4RD0qg5Chd9UOzhzgxXCU0OmgfoMYoxBV3";
    // public static $apiKey = "xnd_production_eKgQjqByK1pkv205lPdZvh0yIBWzQl98dL68OeGerPVUGZJnDu9ItDuAxdR4kK";
    //   public static $apiKey = "xnd_development_OomAfOUth+GowsY6LeJOHzLCZtSj84J9kXDn+Rxj/mHW+byhDQVxhg=="; //staging
    // public static $apiPublicKey = "xnd_public_production_hK4Oi5vfowu6TMB42E5z4qgUYLSKNzL3EYRqJLjwohwzEq8j1Eq24KFGVVK6An";
    // public static $apiPublicKey = "xnd_public_production_vfCF9DRHP7MWw9nMOaOtGgqkfTLcsica0tbDJXTbC0aJpvIBsGrx8ZZrxifBfhQ";
      public static $apiPublicKey = "xnd_public_development_m8jjPs0yWPvgxsw3lNKPrWVSmdi6Ya4eCnTljraLYJZG1qSo9w6GIDZ3FI"; //staging
    public static $apiUrl = "https://api.xendit.co/";
    public static $apiTokenCallback = "7obcCVbtJxz3t0CAajmHupMKT3OuPRDg2YJKeIVTgyrHddjX";

    static function charge($data=null) {
        $data = json_decode($data);
        $resp = [ "status" => false, "account_number" => "" ,  "message" =>"failed" , "code" => "FAILED" ];

        $endpoint = "";
        $payload = [];

        if($data->channel_category == 'VIRTUAL_ACCOUNT') {
            $payload["external_id"] = $data->invoice_number;
            $payload["bank_code"] =  $data->channel_code;
            $payload["name"] = "MBM"; //$data->name;
            $payload["expected_amount"] = $data->amount;
            $payload["amount"] = $data->amount;
            $payload['is_closed'] = true;
            $payload['is_single_use'] = true;
            $payload["expiration_date"] = date('Y-m-d\TH:i:sO',strtotime("+15 minutes ".date("Y-m-d H:i:s")));
            $endpoint = "callback_virtual_accounts";
        }elseif ($data->channel_category == 'EWALLET') {
            $payload["reference_id"] = $data->invoice_number;
            $payload["name"] = "MBM"; //$data->name;
            $payload["currency"] = $data->currency;
            $payload["amount"] = intval($data->total_amount);
            $payload["expected_amount"] = $data->total_amount;
            $payload["checkout_method"] = $data->checkout_method;
            $payload["channel_code"] = 'ID_'.$data->channel_code;

            if(in_array($payload['channel_code'], ["ID_OVO", "ID_DANA", "ID_LINKAJA", "ID_SHOPEEPAY"])) {
                // $payload["channel_properties"]["callback_url"] = "http://api.blocx.id/api/mbm/payment-notification";
                $payload["channel_properties"]["success_redirect_url"] = "http://api.blocx.id/api/mbm/payment-notification";
                $payload["channel_properties"]["failure_redirect_url"] = "http://api.blocx.id/api/mbm/payment-notification";
                $payload["channel_properties"]["mobile_number"] =   "+".$data->phone;
            }else{
                $payload["channel_properties"]["mobile_number"] =   "+".$data->phone;
            }

            $payload["expiration_date"] = date('Y-m-d\TH:i:sO',strtotime("+15 minutes ".date("Y-m-d H:i:s")));
            $endpoint = "ewallets/charges";
        }

        if( count($payload) > 0 && $endpoint!= '' ) {
            $response = self::curl($endpoint,$payload);
            Logger::create([
                'name' => 'mbm_charge',
                'log' => json_encode($response)
            ]);

        }else {
            $response['error_code'] = "FAILED";
            $response['message'] = "FAILED";
            $response['redirect_url'] = null;
        }

        if(isset($response['error_code'])) {
            $resp["message"] = $response['message'];
            $resp["code"] = $response['error_code'];
            $response['redirect_url'] = null;
        }else{
            $resp["status"] = true;
            $resp["message"] = "OK";
            $resp["code"] = "OK";

            if(isset($response['account_number'])) {
                $resp["account_number"] = $response['account_number'];
            }

            if(isset($response['is_redirect_required']) && $response['is_redirect_required'] ) {
                if(isset($response['actions']['mobile_deeplink_checkout_url'])) {
                    $resp['redirect_url'] = $response['actions']['mobile_deeplink_checkout_url'];
                }else {
                    if(isset($response['actions']['mobile_web_checkout_url'])) {
                        $resp['redirect_url'] = $response['actions']['mobile_web_checkout_url'];
                    }
                }
            }else{
                if(isset($response['channel_code']) && in_array($response['channel_code'], ['ID_OVO','ID_SHOPEEPAY'] ) ){
                    $resp["account_number"] = null;
                }else{
                    if(isset($response['account_number'])) {
                        $resp["account_number"] = $response['account_number'];
                    }else{
                        $resp["account_number"] = null;
                    }
                    $resp['redirect_url'] = null;
                }
            }
        }

        return $resp;
    }

    static function curl($endpoint,$data,$method="POST") {
        set_time_limit(0);
        if($method=="GET"){
        //   $data = http_build_query($data);
          if($data)
              $url = self::$apiUrl."/".$endpoint.'/'.$data;
          else
              $url = self::$apiUrl."/".$endpoint;
        }else{
            $url = self::$apiUrl."/".$endpoint;
        }
        $curlHandle = curl_init($url);
        curl_setopt($curlHandle, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($curlHandle, CURLINFO_HEADER_OUT, true);
        curl_setopt($curlHandle, CURLOPT_FAILONERROR, false);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT,0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT,0);
        curl_setopt($curlHandle,CURLOPT_HTTPHEADER,
          [
            "Content-Type: application/json","Accept: application/json",
            "Authorization: Basic ". base64_encode(self::$apiKey . ":" )
          ]
        );
        if($method=="POST"){
          $data = json_encode($data);
          curl_setopt($curlHandle,CURLOPT_POST,true);
          curl_setopt($curlHandle,CURLOPT_POSTFIELDS,$data);
        }

        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($curlHandle, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST,  2);
        $result = curl_exec($curlHandle);
        curl_close($curlHandle);

        return json_decode($result,true);
      }

    static function payload($post = [],$order=null,$mp=null){
        $ok = 1;
        $data['pid'] = $order->pid;
        $data['type']= $mp->type;
        $data['bank_code']= $mp->code;
        $data['member_name']= $order->name;
        $data['reference_id']= $order->invoice_number;
        $data['currency']=	'IDR';
        $data['amount']= $order->amount;
        $data['checkout_method']= 'virtual_acount';
        $data['channel_code']= '';
        $data['account_holder_name']= $order->name;
        $data['account_number']= $order->invoice_number;
        $data['description']= $order->description;

        return [$data,$ok];
    }

    static function save($post=[],$order=null,$result_array=[],$mp=null,$va_number=""){
        $sukses = 1;
        $arrsave = [];

        if($mp){
            $order->mp_id=$mp->id;
            $arrsave[]='mp_id';
        }

        $urlRedirect =  ( isset($result_array['redirect_url']) ?  $result_array['redirect_url'] : null );
        $arrsave[]='order_redirect_url';
        $arrsave[]='order_response';

        if($order->status != 2) {
            $arrsave[]='order_virtual_number';
            $arrsave[]='order_status';
            $order->order_virtual_number = $va_number;
            $order->status = 1;
        }

        $order->order_response = json_encode($result_array);
        $order->order_redirect_url = $urlRedirect;

        if(!$order->update(false,$arrsave)){
            $sukses = 0;
            $urlRedirect = null;
        }

        return [$sukses,$urlRedirect];

    }

    static function eWalletChargeStatus($id){
        $endpoint = "ewallets/charges";

        $response = self::curl($endpoint,$id,'GET');

        return $response;
    }

    static function getPaymentChannel(){
        // $endpoint = 'v2/payment_methods';
        $endpoint = 'payment_channels';

        $data_send = [];
        $resp = [];

        $response = self::curl($endpoint, $data_send, 'GET');

        foreach ($response as $key => $val) {
            if ($val['is_livemode'] == true && $val['is_enabled'] == true) {
                $resp[$val['channel_category']][] = [
                    'name' => $val['name'],
                    'channel_code' => $val['channel_code'],
                    'image' => '',
                    'channel_category' => $val['channel_category'],
                ];
            }
        }

        return $resp;
    }

}
