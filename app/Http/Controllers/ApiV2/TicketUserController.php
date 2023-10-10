<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\TicketUser;
use App\Models\Logger;
use App\Models\LogTiket;
use App\Models\UserApps;
use App\Http\Requests\StoreTicketUserRequest;
use App\Http\Requests\UpdateTicketUserRequest;
use App\Http\Resources\TicketUserResource;

class TicketUserController extends Controller
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
            $ticket = TicketUser::query()->with('ticket', 'order')->where('user_apps_id', auth()->user()->id);

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $ticket = $ticket->orderBy('id', $sort);
            }else {
                $ticket = $ticket->orderBy('id', 'desc');
            }

            if ($request->q) {
                $safetyFields = ['title', 'id'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        if ($field == 'id') {
                            $ticket = $ticket->where($field, '=', $request->q);
                        }else {
                            $ticket = $ticket->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                        }
                    }
                }
            }

            if (isset($request->category))
            {
                $ticket = $ticket->Where([ ['category', '=', $request->category] ]);
            }

            if (isset($request->past) & $request->past == 1)
            {
                $ticket = $ticket->Where([ ['selected_date', '<=', date('Y-m-d')] ]);
            }else {
                // $ticket = $ticket->where([ ['status', '=', 1], ['selected_date', '>=', date('Y-m-d')] ]);
                $ticket = $ticket->where([ ['selected_date', '>=', date('Y-m-d')] ]);
            }

            if (isset($request->type))
            {
                $order = $order->Where([ ['type', '=', $request->type] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $ticket = $ticket->paginate($perPage)->appends($request->query());

            if (count($ticket) < 1) {
                $msg = 'Ticket Data Not Found';
            }

            // $data = $ticket;
            $data = TicketUserResource::collection($ticket);

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
     * @param  \App\Http\Requests\StoreTicketUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTicketUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TicketUser  $ticketUser
     * @return \Illuminate\Http\Response
     */
    public function show(TicketUser $ticketUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTicketUserRequest  $request
     * @param  \App\Models\TicketUser  $ticketUser
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTicketUserRequest $request, TicketUser $ticketUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TicketUser  $ticketUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketUser $ticketUser)
    {
        //
    }

    public function ticketScan(Request $request)
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

            $ticket_user = TicketUser::select('*', 'ticket_users.id as ticket_user_id', 'ticket_users.status as ticket_user_status')->join('tickets', 'ticket_users.ticket_id', '=', 'tickets.id')
                ->join('pricings', 'tickets.id', '=', 'pricings.parent_id')
                ->where('ticket_users.status', '!=', 0)
                ->where(DB::raw('md5(ticket_code::text)') , $request->code)->first();

            if ($ticket_user) {
                $user_apps = UserApps::where('id', $ticket_user->user_apps_id)->first();
                $ticket_log = LogTiket::where([ ['user_apps_id', '=', $user_apps->id], ['fcm_token', '=', $user_apps->fcm_token], ['ticket_user_id', '=', $ticket_user->ticket_user_id] ])->whereDate('date', date('Y-m-d'))->orderByDesc('id')->first();

                switch ($ticket_user->ticket_user_status) {
                    case '1'://checkin
                        if ($ticket_user->pricing_type == 2) {//check ticket harian
                            if (date('Y-m-d', strtotime($ticket_user->selected_date)) == date('Y-m-d')) {
                                if ($ticket_log) {//kalo udah pernah login
                                    if ($ticket_log->fcm_token == $user_apps->fcm_token) {
                                        $update_ticket = TicketUser::where(DB::raw('md5(ticket_code::text)') , $request->code)->update([
                                            'status' => 2
                                        ]);

                                        $new_ticket_log = new LogTiket;
                                        $new_ticket_log->user_apps_id = $user_apps->id;
                                        $new_ticket_log->ticket_user_id = $ticket_user->ticket_user_id;
                                        $new_ticket_log->fcm_token = $user_apps->fcm_token;
                                        $new_ticket_log->date = date('Y-m-d H:i:s');
                                        $new_ticket_log->type = 'checkin';
                                        $new_ticket_log->save();

                                        // $user_apps = UserApps::where('id', $ticket_user->user_apps_id)->first();

                                        if ($user_apps) {
                                            $user_apps->isCheckin = true;
                                            $user_apps->save();
                                        }

                                        $msg = 'Berhasil Berhasil Checkin';
                                    }else {
                                        $msg = 'Anda bukan pemilik tiket';
                                        $success = false;
                                    }
                                }else {//kalo pertama kali login
                                    // $user_apps = UserApps::where('id', $ticket_user->user_apps_id)->first();
                                    $update_ticket = TicketUser::where(DB::raw('md5(ticket_code::text)') , $request->code)->update([
                                        'status' => 2
                                    ]);

                                    $new_ticket_log = new LogTiket;
                                    $new_ticket_log->user_apps_id = $user_apps->id;
                                    $new_ticket_log->ticket_user_id = $ticket_user->ticket_user_id;
                                    $new_ticket_log->fcm_token = $user_apps->fcm_token;
                                    $new_ticket_log->date = date('Y-m-d H:i:s');
                                    $new_ticket_log->type = 'checkin';
                                    $new_ticket_log->save();

                                    if ($user_apps) {
                                        $user_apps->isCheckin = true;
                                        $user_apps->save();
                                    }

                                    $msg = 'Berhasil Checkin';
                                }
                            }else {
                                $msg = 'Anda tidak memiliki tiket untuk hari ini';
                            }
                        }elseif ($ticket_user->pricing_type == 1) {//check tiket all day
                            if ($ticket_log) {//kalo udah pernah login
                                if ($ticket_log->fcm_token == $user_apps->fcm_token) {
                                    // $user_apps = UserApps::where('id', $ticket_user->user_apps_id)->first();
                                    $update_ticket = TicketUser::where(DB::raw('md5(ticket_code::text)') , $request->code)->update([
                                        'status' => 2
                                    ]);

                                    $new_ticket_log = new LogTiket;
                                    $new_ticket_log->user_apps_id = $user_apps->id;
                                    $new_ticket_log->ticket_user_id = $ticket_user->ticket_user_id;
                                    $new_ticket_log->fcm_token = $user_apps->fcm_token;
                                    $new_ticket_log->date = date('Y-m-d H:i:s');
                                    $new_ticket_log->type = 'checkin';
                                    $new_ticket_log->save();
                                    $msg = 'Berhasil Checkin';

                                    if ($user_apps) {
                                        $user_apps->isCheckin = true;
                                        $user_apps->save();
                                    }

                                }else {
                                    $msg = 'Anda bukan pemilik tiket';
                                    $success = false;
                                }
                            }else {//kalo pertama kali login
                                // $user_apps = UserApps::where('id', $ticket_user->user_apps_id)->first();
                                $update_ticket = TicketUser::where(DB::raw('md5(ticket_code::text)') , $request->code)->update([
                                    'status' => 2
                                ]);

                                $new_ticket_log = new LogTiket;
                                $new_ticket_log->user_apps_id = $user_apps->id;
                                $new_ticket_log->ticket_user_id = $ticket_user->ticket_user_id;
                                $new_ticket_log->fcm_token = $user_apps->fcm_token;
                                $new_ticket_log->date = date('Y-m-d H:i:s');
                                $new_ticket_log->type = 'checkin';
                                $new_ticket_log->save();
                                $msg = 'Berhasil Checkin';

                                if ($user_apps) {
                                    $user_apps->isCheckin = true;
                                    $user_apps->save();
                                }
                            }
                        }else {
                            $msg = 'Ticket tidak dikenal';
                        }

                        break;
                    case '2'://checkout
                        if ($ticket_log) {
                            if ($ticket_log->fcm_token == $user_apps->fcm_token) {
                                $update_ticket = TicketUser::where(DB::raw('md5(ticket_code::text)') , $request->code)->update([
                                    'status' => 3
                                ]);

                                $log = new LogTiket;
                                $log->user_apps_id = $user_apps->id;
                                $log->fcm_token = $user_apps->fcm_token;
                                $log->ticket_user_id = $ticket_user->ticket_user_id;
                                $log->date = date('Y-m-d H:i:s');
                                $log->type = 'checkout';
                                $log->save();
                                $msg = 'Berhasil checkout';

                                if ($user_apps) {
                                    $user_apps->isCheckin = false;
                                    $user_apps->save();
                                }
                            }
                        }else {
                            $msg = 'Anda bukan pemilik tiket ini';
                        }

                        break;
                    case '3':
                        if ($ticket_user->pricing_type == 2) {//check ticket harian
                            if (date('Y-m-d', strtotime($ticket_user->selected_date)) == date('Y-m-d')) {
                                if ($ticket_log) {
                                    if ($ticket_log->fcm_token == $user_apps->fcm_token) {
                                        $update_ticket = TicketUser::where(DB::raw('md5(ticket_code::text)') , $request->code)->update([
                                            'status' => 2
                                        ]);

                                        $new_ticket_log = new LogTiket;
                                        $new_ticket_log->user_apps_id = $user_apps->id;
                                        $new_ticket_log->fcm_token = $user_apps->fcm_token;
                                        $new_ticket_log->ticket_user_id = $ticket_user->ticket_user_id;
                                        $new_ticket_log->date = date('Y-m-d H:i:s');
                                        $new_ticket_log->type = 'checkin';
                                        $new_ticket_log->save();
                                        $msg = 'Berhasil Checkin';

                                        // $user_apps = UserApps::where('id', $ticket_user->user_apps_id)->first();
                                        if ($user_apps) {
                                            $user_apps->isCheckin = true;
                                            $user_apps->save();
                                        }

                                    }else {
                                        $msg = 'Anda bukan pemilik tiket';
                                        $success = false;
                                    }
                                }
                            }else {
                                $msg = 'Anda tidak memiliki tiket untuk hari ini';
                            }
                        }elseif ($ticket_user->pricing_type == 1) {//check tiket all day
                            if ($ticket_log) {//kalo udah pernah login
                                if ($ticket_log->fcm_token == $user_apps->fcm_token) {
                                    $update_ticket = TicketUser::where(DB::raw('md5(ticket_code::text)') , $request->code)->update([
                                        'status' => 2
                                    ]);

                                    $new_ticket_log = new LogTiket;
                                    $new_ticket_log->user_apps_id = $user_apps->id;
                                    $new_ticket_log->ticket_user_id = $ticket_user->ticket_user_id;
                                    $new_ticket_log->fcm_token = $user_apps->fcm_token;
                                    $new_ticket_log->date = date('Y-m-d H:i:s');
                                    $new_ticket_log->type = 'checkin';
                                    $new_ticket_log->save();
                                    $msg = 'Berhasil Checkin';

                                    // $user_apps = UserApps::where('id', $ticket_user->user_apps_id)->first();
                                    if ($user_apps) {
                                        $user_apps->isCheckin = true;
                                        $user_apps->save();
                                    }

                                }else {
                                    $msg = 'Anda bukan pemilik tiket';
                                    $success = false;
                                }
                            }
                        }else {
                            $msg = 'Ticket tidak dikenal';
                        }

                        break;

                    default:
                        $msg = 'Mohon Lakukan Pembayaran Ticket';

                        break;
                }
            }else {
                $success = false;
                $msg = 'Ticket Tidak Ditemukan';
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

    public function ticketCheckin(Request $request)
    {
        $success = false;
        $data = [];
        $msg = null;
        try {
            Logger::create([
                'name' => 'EVO_TICKET_CHECKIN',
                'log'  => $request,
            ]);

            Validator::make($request->all(), [
                'code' => 'required',
            ])->validate();

            $ticket_user = select('*', 'ticket_users.id as ticket_user_id')->join('tickets', 'ticket_users.ticket_id', '=', 'tickets.id')
                ->join('pricings', 'tickets.id', '=', 'pricings.parent_id')
                ->whereIn('ticket_users.status', [1, 3])
                ->where('ticket_code' , $request->code)->first();

            if ($ticket_user) {
                $user_apps = UserApps::where('id', auth()->user()->id)->first();
                $ticket_log = LogTiket::where([ ['user_apps_id', '=', $user_apps->id], ['fcm_token', '=', $user_apps->fcm_token] ])->whereDate('date', date('Y-m-d'))->orderByDesc('id')->first();
                // return $ticket_user;
                if ($ticket_user->pricing_type == 2) {//check ticket harian
                    if (date('Y-m-d', strtotime($ticket_user->selected_date)) == date('Y-m-d')) {
                        if ($ticket_log) {//second login
                            if ($ticket_log->fcm_token == $user_apps->fcm_token) {

                                $update_ticket = TicketUser::where('ticket_code' , $request->code)->update([
                                    'status' => 2
                                ]);


                                    $new_ticket_log = new LogTiket;
                                    $new_ticket_log->user_apps_id = $user_apps->id;
                                    $new_ticket_log->fcm_token = $user_apps->fcm_token;
                                    $new_ticket_log->ticket_user_id = $ticket_user->ticket_user_id;
                                    $new_ticket_log->date = date('Y-m-d H:i:s');
                                    $new_ticket_log->type = 'checkin';
                                    $new_ticket_log->save();
                                    $msg = 'Berhasil Checkin';

                                    $user_apps = UserApps::where('id', $user_apps->id)->first();
                                    if ($user_apps) {
                                        $user_apps->isCheckin = true;
                                        $user_apps->save();

                                        $success = true;
                                    }

                            }
                        }else {//first login
                            $update_ticket = TicketUser::where('ticket_code' , $request->code)->update([
                                'status' => 2
                            ]);


                                $new_ticket_log = new LogTiket;
                                $new_ticket_log->user_apps_id = $user_apps->id;
                                $new_ticket_log->ticket_user_id = $ticket_user->ticket_user_id;
                                $new_ticket_log->fcm_token = $user_apps->fcm_token;
                                $new_ticket_log->date = date('Y-m-d H:i:s');
                                $new_ticket_log->type = 'checkin';
                                $new_ticket_log->save();
                                $msg = 'Berhasil Checkin';

                                $user_apps = UserApps::where('id', $user_apps->id)->first();
                                if ($user_apps) {
                                    $user_apps->isCheckin = true;
                                    $user_apps->save();

                                    $success = true;
                                }

                        }
                    }else {
                        $msg = 'Anda tidak memiliki tiket untuk hari ini';
                    }
                }elseif ($ticket_user->pricing_type == 1) {//check ticket all day
                    if ($ticket_log) {
                        if ($ticket_log->fcm_token == $user_apps->fcm_token) {
                            $update_ticket = TicketUser::where('ticket_code' , $request->code)->update([
                                'status' => 2
                            ]);


                            $new_ticket_log = new LogTiket;
                            $new_ticket_log->user_apps_id = $user_apps->id;
                            $new_ticket_log->ticket_user_id = $ticket_user->ticket_user_id;
                            $new_ticket_log->fcm_token = $user_apps->fcm_token;
                            $new_ticket_log->date = date('Y-m-d H:i:s');
                            $new_ticket_log->type = 'checkin';
                            $new_ticket_log->save();
                            $msg = 'Berhasil Checkin';

                            $user_apps = UserApps::where('id', $user_apps->id)->first();
                            if ($user_apps) {
                                $user_apps->isCheckin = true;
                                $user_apps->save();
                            }

                        }else {
                            $update_ticket = TicketUser::where('ticket_code' , $request->code)->update([
                                'status' => 2
                            ]);

                            $new_ticket_log = new LogTiket;
                            $new_ticket_log->user_apps_id = $user_apps->id;
                            $new_ticket_log->fcm_token = $user_apps->fcm_token;
                            $new_ticket_log->ticket_user_id = $ticket_user->ticket_user_id;
                            $new_ticket_log->date = date('Y-m-d H:i:s');
                            $new_ticket_log->type = 'checkin';
                            $new_ticket_log->save();
                            $msg = 'Berhasil Checkin';

                            $user_apps = UserApps::where('id', $user_apps->id)->first();
                            if ($user_apps) {
                                $user_apps->isCheckin = true;
                                $user_apps->save();
                            }

                        }
                    }else {
                        $msg = 'Anda bukan pemilik tiket ini';
                    }
                }else {
                    $msg = 'Ticket tidak dikenal';
                }

            }else {
                $success = false;
                $msg = 'Ticket Tidak Ditemukan';
            }

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $res;
    }

    public function ticketCheckout(Request $request)
    {
        $success = true;
        $data = [];
        $msg = null;
        try {
            // Logger::create([
            //     'name' => 'EVO_TICKET_CHECKOUT',
            //     'log'  => $request,
            // ]);

            Validator::make($request->all(), [
                'code' => 'required',
            ])->validate();

            $ticket_user = TicketUser::select('*', 'ticket_users.id as ticket_user_id')->join('tickets', 'ticket_users.ticket_id', '=', 'tickets.id')
                ->join('pricings', 'tickets.id', '=', 'pricings.parent_id')
                ->where('status', 2)
                ->where('ticket_code' , $request->code)->first();

            if ($ticket_user) {
                $msg = 'ticket_user';
                $user_apps = UserApps::where('id', auth()->user()->id)->first();
                $ticket_log = LogTiket::where([ ['user_apps_id', '=', $user_apps->id], ['fcm_token', '=', $user_apps->fcm_token] ])->whereDate('date', date('Y-m-d'))->orderByDesc('id')->first();

                if ($ticket_log) {$msg = 'ticket_log';
                    if ($ticket_log->fcm_token == $user_apps->fcm_token) {
                        $update_ticket = TicketUser::where('ticket_code' , $request->code)->update([
                            'status' => 3
                        ]);

                        $new_ticket_log = new LogTiket;
                        $new_ticket_log->user_apps_id = $user_apps->id;
                        $new_ticket_log->fcm_token = $user_apps->fcm_token;
                        $new_ticket_log->date = date('Y-m-d H:i:s');
                        $new_ticket_log->type = 'checkout';
                        $new_ticket_log->save();
                        $msg = 'Berhasil Checkout';

                        if ($user_apps) {
                            $user_apps->isCheckin = false;
                            $user_apps->save();
                        }

                    }
                }else {
                    $msg = 'ticket_not_log';
                    $msg = 'Anda bukan pemilik tiket ini';
                }

            }else {
                $success = false;
                $msg = 'Ticket Tidak Ditemukan';
            }

        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }

        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
        ];

        return $res;
    }

    public function redeemTicket(Request $post){
        $success = true;
        $data = [];
        $msg = 'Ok';
        $quantity = 1;

        try {
            $ticket_user = TicketUser::where([ ['ticket_code', '=', $post->ticket_code] ])->first();

            if (!$ticket_user) {
                $success = false;
                throw new \Exception("Ticket Nout Found");
            }

            $existing_ticket_user = TicketUser::where([ ['user_apps_id', '=', auth()->user()->id], ['ticket_id', '=', $ticket_user->ticket_id], ['status', '=', 1] ])->exists();

            if ($existing_ticket_user) {
                throw new \Exception("Anda sudah memiliki ticket");
            }

            if ($ticket_user->quantity > 1) {
                $quantity = $ticket_user->quantity - 1;

                $ticket = new TicketUser;
                $ticket->ticket_id = $ticket_user->ticket_id;
                $ticket->pricing_id = $ticket_user->pricing_id;
                $ticket->user_apps_id = auth()->user()->id;
                $ticket->customer_name = auth()->user()->name;
                $ticket->customer_email = auth()->user()->email;
                $ticket->customer_phone = auth()->user()->phone;
                $ticket->ticket_code = $ticket_user->ticket_code;
                $ticket->based_price = $ticket_user->based_price;
                $ticket->discount = $ticket_user->discount;
                $ticket->price = $ticket_user->price;
                $ticket->quantity = 1;
                $ticket->ticket_type = 1;
                $ticket->selected_date = isset($ticket_user->selected_date) ? date('Y-m-d', strtotime($ticket_user->selected_date)) : null;
                $ticket->status = 1;
                // $ticket->status = 1;
                $ticket->invoice_number = $ticket_user->invoice_number;
                $ticket->description = 'Redeemed Ticket';
                $ticket->save();

                $ticket_user = $ticket_user->update([
                    'quantity' => $quantity
                ]);
            }else {
                $msg = 'Redeemed voucher failed';
                $success = false;
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
}
