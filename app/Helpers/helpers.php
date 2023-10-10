<?php

use App\Models\TicketOrder;
use App\Models\EatsOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('img_enc_base64')) {
    function img_enc_base64 ($filepath){   // img_enc_base64() is manual function you can change the name what you want.
        if (file_exists($filepath)){

            $filetype = pathinfo($filepath, PATHINFO_EXTENSION);

            if ($filetype==='svg'){
                $filetype .= '+xml';
            }

            $get_img = file_get_contents($filepath);
            return 'data:image/' . $filetype . ';base64,' . base64_encode($get_img );
        }
    }
}

if (! function_exists('base64ToImage')) {
    function base64ToImage ($file){   // base64ToImage() is manual function you can change the name what you want.
        $success = true;
        $data = null;
        $msg = null;
        try {
            $image = $file;  // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $extension = explode('/', mime_content_type($file))[1];
            $imageName = md5(microtime()) .'.'.$extension;
            $status = \File::put(public_path(). '/img/' . $imageName, base64_decode($image));
            $data = $imageName;
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }
        return [
            'success' => $success,
            'data' => $data,
            'msg' => $msg,
        ];
    }
}

if (! function_exists('payToMidtrans')) {
    function payToMidtrans ($data){
        $response = null;
        $success = true;
        $msg = null;
        $paymentResponse = null;
        $sessionId = sha1($data["id"]);
        $body = [
            'amount' => $data["amount"],
            'session-id' => $sessionId,
            'access-key' => sha1(config('app.pr_client_key').$data["amount"].$sessionId),
            'client-id' => config('app.pr_client_id'),
            'product-desc' => $data["product-desc"],
        ];
        try {
            $url = config('app.pr_endpoint');
            $response = Http::asForm()->post($url, $body);
            if (!empty($response->json()["message-pr"])) {
                $msg = $response->json()["message-pr"];
            }
            $jsonData = $response->json();
            if ($response->successful() && !empty($jsonData)) {
                if ($jsonData) {
                    $paymentResponse = $jsonData;
                    if($jsonData && $jsonData["status-pr"] == 1){
                        $tmp = TicketOrder::find($data["id"]);
                        if (!$tmp) {
                            $tmp = EatsOrder::find($data["id"]);
                        }
                        $tmp->update([
                            'invoice-id' => $jsonData["invoice-id"]
                        ]);
                    }
                    if($jsonData && $jsonData["status-pr"] == 0){
                        $success = false;
                    }
                } else {
                    $success = false;
                }
            }


            if ($response->failed()) {
                $success = false;
            }
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $paymentResponse,
            'message' => $msg,
            'request' => $body,
        ];
        return $res;
    }
}
