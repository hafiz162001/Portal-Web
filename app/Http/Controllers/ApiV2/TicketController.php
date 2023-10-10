<?php

namespace App\Http\Controllers\ApiV2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Ticket;
use App\Models\TicketUser;
use App\Models\Order;
use App\Models\Logger;
use App\Models\Pricing;
use App\Models\UserApps;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\EvoriaTicketResource;
use Illuminate\Support\Str;

use App\Mail\myMailer;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';
        $perPage = 5;

        try {
            $tickets = Ticket::query()->with('galleries', 'pricing');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $tickets = $tickets->orderBy('id', $sort);
            }else {
                $tickets = $tickets->orderBy('sort_num', 'asc');
            }

            if ($request->q) {
                $safetyFields = ['title', 'id'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        if ($field == 'id') {
                            $tickets = $tickets->where($field, '=', $request->q);
                        }else {
                            $tickets = $tickets->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                        }
                    }
                }
            }

            if (isset($request->category))
            {
                $tickets = $tickets->Where([ ['category', '=', $request->category] ]);
            }

            if (isset($request->type))
            {
                $tickets = $tickets->Where([ ['type', '=', $request->type] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $tickets = $tickets->paginate($perPage)->appends($request->query());

            if (count($tickets) < 1) {
                $msg = 'Vies Data Not Found';
            }

            // $data = $tickets;
            $data = EvoriaTicketResource::collection($tickets);

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];

        return $resp;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTicketRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = false;
        $data = [];
        $msg = 'Gagal';

        Logger::create([
            'name' => 'Ticket_website',
            'log'  => $request
        ]);

        $ticket_id = 5;
        $pricing_id = 5;
        $quantity = 1;
        $total_ticket = 0;

        if (isset($request->pricing_id)) {
            $ticket_id = $request->id;
            $pricing_id = $request->pricing_id;
        }

        if (isset($request->quantity)) {
            $quantity = $request->quantity;
        }

        try {
            $invoice_number = Str::random(4).'-'.time();

            $pricing = Pricing::where('id', $pricing_id)->first();
            $ticket_master = Ticket::where('id', '=', $ticket_id)->first();
            $ticket_active = TicketUser::selectRaw(" sum(quantity) as total ")->where([ ['ticket_id', '=', $ticket_id], ['status', '=', 1] ])->first();

            if ($ticket_active) {
                $total_ticket = $ticket_active->total;
            }
            if (!$pricing) {
                throw new \Exception("Pricing Ticket Nout Found");
            }

            $total_ticket = $total_ticket + $quantity;

            if (!$ticket_master) {
                throw new \Exception("Ticket Nout Found");
            }

            if ($pricing->quota < $total_ticket) {
                throw new \Exception("Ticket sudah habis");
            }

            $based_price = $pricing->price;
            $discount = 0;
            $price = $based_price * $quantity;

            $user_apps = UserApps::where([ ['email', '=', $request->email], ['user_category', '=', 'evoria'] ])->first();

            if (!$user_apps) {
                $user_apps_id = UserApps::insertGetId([
                    'name' => $request->nama,
                    'phone' => $request->no_telp,
                    'email' => $request->email,
                    'address' => $request->alamat,
                    'user_category' => 'evoria',
                    'isRegistered' => true,
                    'gender' => $request->gender,
                    'dob' => date('Y-m-d', strtotime($request->tgl_lahir)),
                    'question1' => $request->question1,
                ]);

                $user_apps = UserApps::where([ ['id', '=', $user_apps_id], ['user_category', '=', 'evoria'] ])->first();
            }else {
                $user_apps->gender = $request->gender;
                $user_apps->dob = date('Y-m-d', strtotime($request->tgl_lahir));
                $user_apps->question1 = $request->question1;
                $user_apps->name = $request->nama;
                $user_apps->phone = $request->no_telp;
                $user_apps->isRegistered = true;

                $user_apps->update();
            }

            $status_tiket = 0;

            if ($price == 0) {
                $status_tiket = 1;
            }

            if ($user_apps->member_right == 1) {
                $price = 500;
            }

            $success = true;
            $msg = "Berhasil";

            for ($i=0; $i < $quantity; $i++) {
                $ticket_code = 'TICKET'.Str::random(4).time();

                $ticket = new TicketUser;
                $ticket->user_apps_id = $user_apps->id;
                $ticket->customer_name = $user_apps->name;
                $ticket->customer_email = $user_apps->email;
                $ticket->customer_phone = $user_apps->phone;
                $ticket->selected_date = date('Y-m-d', strtotime($ticket_master->started_date));
                $ticket->ticket_id = $ticket_id;
                $ticket->pricing_id = $pricing_id;
                $ticket->ticket_code = $ticket_code;
                $ticket->based_price = $based_price;
                $ticket->discount = $discount;
                $ticket->price = $price;
                $ticket->quantity = 1;
                $ticket->ticket_type = 1;
                // $ticket->selected_date = isset($request->tanngal) ? $request->tanggal : null;
                $ticket->status = $status_tiket;
                // $ticket->status = 1;
                $ticket->invoice_number = $invoice_number;
                $ticket->description = 'ticket web';
                $ticket->type = 'ticket_web';
                $ticket->save();
            }

            $order = new Order;
            $order->user_apps_id = $user_apps->id;
            $order->customer_name = $user_apps->name;
            $order->customer_email = $user_apps->email;
            $order->customer_phone = $user_apps->phone;
            $order->invoice_number = $invoice_number;
            $order->order_type = 'ticket';
            $order->based_price = $based_price;
            $order->discount = $discount;
            $order->price = $price;
            $order->quantity = $quantity;
            $order->status = $status_tiket;
            // $order->status = 1;
            $order->description = 'ticket web';
            $order->save();

            if ($status_tiket == 0) {
                $dataSend = [
                    'id' => $order->id,
                    'amount' => $order->price,
                    'product-desc' => 'evoria ticket',
                ];

                $paymentResponse = payToMidtrans($dataSend);

                if(!empty($paymentResponse)){
                    if(!empty($paymentResponse["data"]) && !empty($paymentResponse["data"]["invoice-id"])){
                        Order::where('id', $dataSend['id'])->update([
                            'invoice_number' => $paymentResponse["data"]["invoice-id"],
                            'redirect_url' => $paymentResponse["data"]["redirect_url"],
                        ]);
                        TicketUser::where('invoice_number', $invoice_number)->update([
                            'invoice_number' => $paymentResponse["data"]["invoice-id"],
                        ]);
                    }

                    $data = $paymentResponse;
                }
            }else {
                $ticket_user = TicketUser::where([ ['invoice_number', '=', $invoice_number] ])->take($quantity)->get();
                $link = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=";

                if (count($ticket_user) > 0) {
                    foreach ($ticket_user as $key => $val) {
                        $otp[] = $link.md5($val['ticket_code']);
                    }

                    $details = [
                        'title' => 'Evoria',
                        'desc' => 'Your Qr Code',
                        'otp' => $otp,
                        'email' => $order->customer_email,
                    ];

                    $response = Mail::to($details['email'])->send(new \App\Mail\myMailer($details));

                    Logger::create([
                        'name' => 'Ticket_website_email',
                        'log'  => json_encode($response)
                    ]);
                }
            }

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
            // $msg = config('app.error_message');
        }

        $resp = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $resp;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTicketRequest  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }

    public function ticketScan(Request $request)
    {
        $status = false;
        $data = [];
        $msg = null;

        Logger::create([
                'name' => 'TICKET_SCAN',
                'log'  => $request
            ]);

        try {
            $ticket_user = TicketUser::where('status', '!=', 0)
            ->where(DB::raw('md5(ticket_code::text)') , $request->code)->first();

            if ($ticket_user) {
                switch ($ticket_user->status) {
                    case '1':
                        $ticket_user->status = 2;
                        $ticket_user->save();
                        $msg = 'Berhasil Checkin';
                        $status = true;
                        break;
                    case '2':
                        $ticket_user->status = 3;
                        $ticket_user->save();
                        $msg = 'Berhasil Checkout';
                        $status = true;
                        break;

                    default:
                        $msg = 'Mohon Lakukan Pembayaran Ticket';
                        break;
                }
            }

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }

        $res = [
            'success' => $status,
            'data'    => $data,
            'message' => $msg,
            'request' => $request->all(),
        ];

        return $res;
    }

    function genereateQrCode($codes){
        $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
        $qr_name = 'QR-'.Str::random(4).time().'.png';
        QrCode::format('png')->generate(md5('codes'), $qr_name, $destinationPath);

        return $qr_name;
    }
}
