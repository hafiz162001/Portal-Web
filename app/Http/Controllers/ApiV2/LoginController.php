<?php

namespace App\Http\Controllers\ApiV2;

use App\Http\Controllers\Controller;
use App\Http\Library\Pawoon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserApps;
use Validator;
use Otp;
use SMS;
use App\Http\Resources\UserResource;
use App\Models\Beacon;
use GuzzleHttp\Client;
use Infobip\Api\SendSmsApi;
use Infobip\Configuration;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use App\Models\OtpUserApps;
// use Str;
use Illuminate\Support\Str;

use App\Mail\myMailer;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function otp(Request $request)
    {
        $success = true;
        $data = null;
        $msg = null;
        $send_otp = true;

        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
            ])->validate();

            $phone = $request->phone;

            // jika masih ada otp belum dipakai, maka gunakan itu
            // jika tidak, generate otp baru

            $lastOtpLog = OtpUserApps::where('phone', $phone)
            ->latest()
            ->first();
            // $oldCondition = empty($lastOtpLog) || (!empty($lastOtpLog) && $lastOtpLog->isUsed);
            if(true){
                // generate otp disini
                $data = Otp::generate($phone);
                if($phone == "6283891122801" || $phone == "6282213231071" || $phone == "6281388325621" || $phone == "6282119249249"){
                    $send_otp = false;
                    $data = "0912";
                }
                $MESSAGE_TEXT = 'Your OTP code is ' . $data;
                $smsResponse = "";
                // ==========================================================


                // sendinblue
                // $config = \SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', config('app.sendinblueKey'));
                // $apiInstance = new \SendinBlue\Client\Api\TransactionalSMSApi(
                //     new \GuzzleHttp\Client(),
                //     $config
                // );

                // $sendTransacSms = new \SendinBlue\Client\Model\SendTransacSms();
                // $sendTransacSms['sender'] = 'MBloc';
                // $sendTransacSms['recipient'] = $phone;
                // $sendTransacSms['content'] = 'Your OTP code is ' . $data;
                // $sendTransacSms['type'] = 'transactional';
                // $apiInstance->sendTransacSms($sendTransacSms);

                if ($send_otp) {//jika akun tester ga usah kirim otp
                    // SEND IN BLUE
                    if(config('app.sendinblueActivated')){
                        // $MESSAGE_TEXT = 'Your OTP code is ' . $data;
                        // $smsResponse = "";
                        try {
                            $sms = SMS::send(function($sms) use($MESSAGE_TEXT, $phone){
                                $sms->to($phone);
                                $sms->sender('MBloc');
                                $sms->message($MESSAGE_TEXT);
                            });
                        } catch (\Throwable $th) {
                            $smsResponse = $th->getMessage();
                        }
                        $otpLog = OtpUserApps::create([
                            'phone' => $phone,
                            'code' => $data,
                            'message' => $MESSAGE_TEXT,
                            'response' => json_encode($smsResponse),
                        ]);
                    }else {
                        $otpLog = OtpUserApps::create([
                            'phone' => $phone,
                            'code' => $data,
                            'message' => $MESSAGE_TEXT,
                            'response' => json_encode($smsResponse),
                        ]);
                    }
                }else {
                    $otpLog = OtpUserApps::create([
                        'phone' => $phone,
                        'code' => $data,
                        'message' => $MESSAGE_TEXT,
                        'response' => json_encode($smsResponse),
                    ]);
                }

                // infobip
                // if(config('app.infobip_activated')){
                //     $SENDER = "MBloc";
                //     $RECIPIENT = $phone;
                //     $MESSAGE_TEXT = 'Your OTP code is ' . $data;

                //     $configuration = (new Configuration())
                //     ->setHost(config('app.infobip_base_url'))
                //     ->setApiKeyPrefix('Authorization', 'App')
                //     ->setApiKey('Authorization', config('app.infobip_api_key'));
                //     $client = new Client();
                //     $sendSmsApi = new SendSMSApi($client, $configuration);
                //     $destination = (new SmsDestination())->setTo($RECIPIENT);
                //     $message = (new SmsTextualMessage())
                //         ->setFrom($SENDER)
                //         ->setText($MESSAGE_TEXT)
                //         ->setDestinations([$destination]);
                //     $request = (new SmsAdvancedTextualRequest())->setMessages([$message]);
                //     $smsResponse = $sendSmsApi->sendSmsMessage($request);
                //     $otpLog = OtpUserApps::create([
                //         'phone' => $phone,
                //         'code' => $data,
                //         'message' => $MESSAGE_TEXT,
                //         'response' => json_encode($smsResponse),
                //     ]);
                // }
            }else{
                // langsung lanjut ke validate dan check otp terakhir
                if(!config('app.infobip_activated')){
                    $data = Otp::generate($phone);
                    if($phone == "6283891122801" || $phone == "6282213231071" || $phone == "6281388325621"  || $phone == "6282119249249"){
                        $send_otp = false;
                        $data = "0912";
                    }
                }

                if ($send_otp) {//jika akun tester ga usah kirim otp
                    if(!empty($lastOtpLog)){

                        if(config('app.sendinblueActivated')){
                            $MESSAGE_TEXT = 'Your OTP code is ' . $data;
                            $smsResponse = "";
                            try {
                                $sms = SMS::send(function($sms) use($MESSAGE_TEXT, $phone){
                                    $sms->to($phone);
                                    $sms->sender('MBloc');
                                    $sms->message($MESSAGE_TEXT);
                                });
                            } catch (\Throwable $th) {
                                $smsResponse = $th->getMessage();
                            }
                            $otpLog = OtpUserApps::create([
                                'phone' => $phone,
                                'code' => $data,
                                'message' => $MESSAGE_TEXT,
                                'response' => json_encode($smsResponse),
                            ]);
                        }else {
                            $otpLog = OtpUserApps::create([
                                'phone' => $phone,
                                'code' => $data,
                                'message' => $MESSAGE_TEXT,
                                'response' => '',
                            ]);
                        }
                    }
                }
            }

        } catch (\Throwable $th) {
            $success = false;
            // $msg = config('app.error_message');
            $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];
        return $res;
    }

    public function validateOtp(Request $request)
    {
        $success = true;
        $data = null;
        $user = null;
        $msg = null;
        $beacons = [];
        try {
            $request->validate([
                'email' => 'required',
                'otp' => 'required',
            ]);
            $email = strtolower($request->email);
            $status = false;
            $isReviewer = false;
            $lastOtpLog = OtpUserApps::where('email', $email)
            ->latest()
            ->first();
            // =====================================
            if(!$isReviewer){
                if(!config('app.infobip_activated')){
                    if(!empty($lastOtpLog) && !$lastOtpLog->isUsed){
                        $status = $lastOtpLog->code == $request->otp;
                    }else{
                        $result = collect(Otp::validate($email, $request->otp));
                        $status = $result["status"];
                    }
                }else{
                    // use otp code activated
                    if(!empty($lastOtpLog) && !$lastOtpLog->isUsed){
                        $status = $lastOtpLog->code == $request->otp;
                    }else{
                        throw new \Exception("Invalid Data");
                    }
                }
            }
            // create token in here
            if($status || $isReviewer){
                $user = UserApps::where([ ['user_category', '=', 'evoria'], ['email', '=', $email] ])->with('contestan')->withTrashed()->first();

                if ($user && $user->deleted_at != null) {
                    throw new \Exception("Akun anda sudah tidak aktif, silahkan menghubungi admin");
                }

                if(!$user){
                    $user_create = UserApps::insertGetId([
                        'email' => $email,
                        'user_category' => 'evoria',
                    ]);

                    $user = UserApps::where([ ['id', '=', $user_create] ])->with('contestan')->first();
                }

                if (isset($request->fcm_token)) {
                    $user->fcm_token = $request->fcm_token;
                    $user->save();
                }

                $user_resource = UserResource::make($user);
                $data = $user_resource->createToken($email)->plainTextToken;
                if($data){
                    $lastOtpLog->update([
                        'isUsed' => true,
                    ]);
                    $beacons = Beacon::all();
                }
            }else{
                // $result["error"]
                throw new \Exception(config('app.error_message'));
            }
        } catch (\Throwable $th) {
            $success = false;
            // $msg = config('app.error_message');
            $msg = $th->getMessage();
        }
        $res = [
            'success' => $success,
            'data'    => [
                'token'  => $data,
                'user'   => $user,
                'beacon' => $beacons
            ],
            'message' => $msg,
            'request' => $request->all(),
        ];
        return $res;
    }

    public function updateProfile(Request $request)
    {
        $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
        $success = true;
        $data = null;
        $msg = null;
        $kontestan_open = false;

        try {
            if(!empty($request->email) && ($request->email != auth()->user()->email)){
                $request->validate([
                    'email' => 'email|unique:user_apps,email',
                ]);
            }
            $request->merge(['isRegistered' => true, 'user_category' => 'evoria']);
            $user = auth()->user();
            $data = $user->update($request->except(['phone', 'foto', 'foto_sampul', 'dob']));
            if($data){
                // if (!empty($request->foto)) {
                //     $resp = base64ToImage($request->foto);
                //     if($resp["success"]){
                //         $user->foto = $resp["data"];
                //     }else{
                //         throw new \Exception($resp["msg"]);
                //     }
                // }
                if (!empty($request->foto)) {
                    $file      = $request->foto;
                    $parts     = explode(";base64,", $file);
                    $fileparts = explode("image/", @$parts[0]);
                    $filetype  = $fileparts[1];
                    $fileName  = md5(microtime()). '.' . $filetype;
                    \File::put($destinationPath. '/' . $fileName, base64_decode($parts[1]));

                    $user->foto = $fileName;
                }

                if (!empty($request->dob)) {
                    $user->dob = date('Y-m-d', strtotime($request->dob));
                }
                // if (!empty($request->foto_sampul)) {
                //     $resp = base64ToImage($request->foto_sampul);
                //     if($resp["success"]){
                //         $user->foto_sampul = $resp["data"];
                //     }else{
                //         throw new \Exception($resp["msg"]);
                //     }
                // }

                if (!empty($request->foto_sampul)) {
                    $file      = $request->foto_sampul;
                    $parts     = explode(";base64,", $file);
                    $fileparts = explode("image/", @$parts[0]);
                    $filetype  = $fileparts[1];
                    $fileName  = md5(microtime()). '.' . $filetype;
                    \File::put($destinationPath. '/' . $fileName, base64_decode($parts[1]));

                    $user->foto_sampul = $fileName;
                }
                $user->update();
            }

            if (date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime('2022-12-10 23:59:59'))) {
                $kontestan_open = true;
            }
        } catch (\Throwable $th) {
            $success = false;
            // $msg = config('app.error_message');
            $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => new UserResource(auth()->user()),
            'contestan_open' => $kontestan_open,
            'message' => $msg,
            'request' => $request->all(),
        ];
        return $res;
    }

    public function profile(Request $request)
    {
        $success = true;
        $data = null;
        $msg = null;
        try {
            $userApps = UserApps::where([ ['id', '=', auth()->user()->id], ['user_uuid', '=', null] ] )->update(
                ['user_uuid' => Str::uuid(auth()->user()->id)]
            );

            $data = UserApps::where('id', auth()->user()->id)->first();

            // $data = auth()->user();
        } catch (\Throwable $th) {
            $success = false;
            $msg = config('app.error_message');
            // $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => new UserResource($data),
            'message' => $msg,
            'request' => $request->all(),
        ];
        return $res;
    }

    public function login(Request $request)
    {
        $success = true;
        $data = null;
        $msg = null;
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'device_name' => 'required',
            ]);

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $auth = Auth::user();
                $data =  $auth->createToken($request->device_name)->plainTextToken;
            } else {
                throw new \Exception("Unauthorized");
            }
        } catch (\Throwable $th) {
            $success = false;
            $msg = config('app.error_message');
            // $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'request' => $request->all(),
        ];
        return $res;
    }

    public function register(Request $request)
    {
        $success = true;
        $data = null;
        $msg = null;
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->messages()->first());
            }

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $data =  $user->createToken('LaravelSanctumAuth')->plainTextToken;
        } catch (\Throwable $th) {
            $success = false;
            $msg = config('app.error_message');
            // $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'request' => $request->all(),
        ];
        return $res;
    }

    private function generateQr(){

    }

    public function showQr(Request $post){
        $success = true;
        $data = null;
        $msg = null;

        try {
            $userApps = UserApps::where([ ['id', '=', auth()->user()->id], ['user_uuid', '=', null] ] )->update(
                ['user_uuid' => Str::uuid(auth()->user()->id)]
            );

            $data = UserApps::where('id', auth()->user()->id)->first();
        } catch (\Throwable $th) {
            $success = false;
            // $msg = config('app.error_message');
            $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];
        return $res;
    }

    public function deleteAccount(Request $request){
        $success = true;
        $data = null;
        $msg = null;

        try {
            if (isset($request->id)) {
                $delete = UserApps::where('id', $request->id)->delete();
                $data = $delete;
            }
        } catch (\Throwable $th) {
            $success = false;
            // $msg = config('app.error_message');
            $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $res;
    }

    public function auth(Request $request){
        $success = true;
        $data = null;
        $msg = null;

        try {
            $data = Pawoon::oauth();
        } catch (\Throwable $th) {
            $success = false;
            // $msg = config('app.error_message');
            $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $res;
    }

    public function outlets(Request $request){
        $success = true;
        $data = null;
        $msg = null;

        try {
            $data = Pawoon::outlets();
        } catch (\Throwable $th) {
            $success = false;
            // $msg = config('app.error_message');
            $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $res;
    }

    public function products(Request $request){
        $success = true;
        $data = null;
        $msg = null;

        try {
            $data = Pawoon::products();
        } catch (\Throwable $th) {
            $success = false;
            // $msg = config('app.error_message');
            $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $res;
    }


    public function email(Request $request)
    {
        $success = true;
        $data = null;
        $msg = null;
        $send_otp = true;

        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
            ])->validate();

            $email = strtolower($request->email);

            // $checkEmail = UserApps::where('email', $request->email)->first();
            // if ($checkEmail) {
            //     if ($checkEmail->user_category != 'evoria') {

            //         $res = [
            //             'success' => false,
            //             'data'    => $data,
            //             'message' => 'Email sudah di gunakan',
            //         ];

            //         return $res;
            //     }
            // }

            // jika masih ada otp belum dipakai, maka gunakan itu
            // jika tidak, generate otp baru

            $lastOtpLog = OtpUserApps::where('email', $email)
            ->latest()
            ->first();
            // $oldCondition = empty($lastOtpLog) || (!empty($lastOtpLog) && $lastOtpLog->isUsed);
            if(true){
                // generate otp disini
                $data = Otp::generate($email);

                if ($email == 'adit18ia@gmail.com' || $email == 'testing1@gmail.com' || $email == 'killman8898@gmail.com' || $email == 'amahilman@gmail.com' ) {
                    $data = '0000';
                    $send_otp = false;
                }

                $MESSAGE_TEXT = 'Your OTP code is ' . $data;
                $response = "";
                // ==========================================================


                if ($send_otp) {//jika akun tester ga usah kirim otp
                    // if(config('app.sendinblueActivated')){
                        try {
                            $details = [
                                'title' => 'Mail From Evoria',
                                'otp' => $data,
                                'email' => $email,
                                'desc' => 'Your OTP',
                            ];

                            $response = Mail::to($details['email'])->send(new \App\Mail\myMailer($details));

                            // $sendmail = Mail::send(function($mail) use($details){
                            //     $mail->to($details['email']);
                            //     $mail->sender('MBloc');
                            // });
                        } catch (\Throwable $th) {
                            $response = $th->getMessage();
                        }

                        $otpLog = OtpUserApps::create([
                            'email' => $email,
                            'code' => $data,
                            'message' => $MESSAGE_TEXT,
                            'response' => json_encode($response),
                        ]);
                    // }else {
                    //     $otpLog = OtpUserApps::create([
                    //         'email' => $email,
                    //         'code' => $data,
                    //         'message' => $MESSAGE_TEXT,
                    //         'response' => json_encode($response),
                    //     ]);
                    // }
                }else {
                    $otpLog = OtpUserApps::create([
                        'email' => $email,
                        'code' => $data,
                        'message' => $MESSAGE_TEXT,
                        'response' => json_encode($response),
                    ]);
                }
            }else{
                // langsung lanjut ke validate dan check otp terakhir
                // $data = Otp::generate($email);
                if ($email == 'adit18ia@gmail.com' || $email == 'testing1@gmail.com' || $email == 'killman8898@gmail.com' || $email == 'amahilman@gmail.com' ) {
                    $data = '0000';
                    $send_otp = false;
                }

                if ($send_otp) {//jika akun tester ga usah kirim otp
                    if(!empty($lastOtpLog)){
                        $data = $lastOtpLog->code;
                        $MESSAGE_TEXT = 'Your OTP code is ' . $data;

                        $details = [
                            'title' => 'Mail From Evoria',
                            'otp' => $data,
                            'email' => $email,
                            'desc' => 'Your OTP'
                        ];

                        $response = Mail::to($details['email'])->send(new \App\Mail\myMailer($details));
                    }
                }
            }

        } catch (\Throwable $th) {
            $success = false;
            // $msg = config('app.error_message');
            $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];
        return $res;
    }
}
