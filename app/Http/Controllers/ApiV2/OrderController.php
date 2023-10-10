<?php

namespace App\Http\Controllers\ApiV2 ;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\TicketUser;
use App\Models\Pricing;
use App\Models\Order;
use App\Models\Voucher;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

use Illuminate\Support\Str;

class OrderController extends Controller
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
            $order = Order::query()->with('galleries', 'pricing');

            if ($request->sort) {
                $sort = ['asc', 'desc'];
                $sort = !in_array(strtolower($request->sort), $sort) ? 'asc' : $request->sort;

                $order = $order->orderBy('id', $sort);
            }else {
                $order = $order->orderBy('id', 'desc');
            }

            if ($request->q) {
                $safetyFields = ['title', 'id'];

                foreach ($request->fields as $field) {
                    if (in_array(strtolower($field), $safetyFields)) {
                        if ($field == 'id') {
                            $order = $order->where($field, '=', $request->q);
                        }else {
                            $order = $order->orWhere($field, 'ILIKE', '%' . $request->q . '%');
                        }
                    }
                }
            }

            if (isset($request->category))
            {
                $order = $order->Where([ ['category', '=', $request->category] ]);
            }

            if (isset($request->type))
            {
                $order = $order->Where([ ['type', '=', $request->type] ]);
            }

            if ($request->perPage) {
                $perPage = $request->perPage;
            }

            $order = $order->paginate($perPage)->appends($request->query());

            if (count($order) < 1) {
                $msg = 'Order Data Not Found';
            }

            // $data = $order;
            $data = OrderResource::collection($order);

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
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = true;
        $data = [];
        $msg = 'Ok';
        $quantity = 1;
        $voucher_code = '';
        $total_ticket = 0;

        try {
            $invoice_number = Str::random(4).'-'.time();
            $ticket_code = 'TICKET'.Str::random(4).time();
            $ticket_active = TicketUser::selectRaw(" sum(quantity) as total ")->where([ ['ticket_id', '=', $request->ticket_id], ['status', '=', 1] ])->first();

            if ($ticket_active) {
                $total_ticket = $ticket_active->total;
            }

            if (isset($request->voucher_code)) {
                $voucher = Voucher::where('voucher_code', $request->voucher_code)->where('status', 1)->first();
            }

            if (isset($request->quantity)) {
                $quantity = $request->quantity;
            }

            $pricing = Pricing::where('id', $request->pricing_id)->first();

            if (!$pricing) {
                throw new \Exception("Pricing Ticket Nout Found");
            }

            $total_ticket = $total_ticket + $quantity;

            if ($pricing->quota < $total_ticket) {
                throw new \Exception("Ticket sudah habis");
            }

            $discount = 0;

            if (isset($voucher)) {
                $discount = $voucher->amount;
                $voucher_code = $voucher->voucher_code;
            }

            $based_price = $pricing->price * $quantity;
            $price = $based_price - $discount;
            $status_tiket = 0;

            if (auth()->user()->member_right == 1) {
                $price = 10000;
            }

            if ($price <= 0) {
                $status_tiket = 1;
            }

            $success = true;
            $msg = "Berhasil";

            $ticket_user = TicketUser::where([ ['user_apps_id', '=', auth()->user()->id], ['ticket_id', '=', $request->ticket_id], ['pricing_id', '=', $request->pricing_id], ['status', '=', 0] ])->latest()->first();
            if (!$ticket_user) {
                $ticket = new TicketUser;
                $ticket->ticket_id = $request->ticket_id;
                $ticket->pricing_id = $request->pricing_id;
                $ticket->user_apps_id = auth()->user()->id;
                $ticket->customer_name = auth()->user()->name;
                $ticket->customer_email = auth()->user()->email;
                $ticket->customer_phone = auth()->user()->phone;
                $ticket->ticket_code = $ticket_code;
                $ticket->based_price = $based_price;
                $ticket->discount = $discount;
                $ticket->price = $price;
                $ticket->quantity = $quantity;
                $ticket->ticket_type = 1;
                $ticket->selected_date = isset($request->selected_date) ? $request->selected_date : null;
                $ticket->status = $status_tiket;
                $ticket->invoice_number = $invoice_number;
                $ticket->description = null;
                $ticket->save();

                $order = new Order;
                $order->user_apps_id = auth()->user()->id;
                $order->customer_name = auth()->user()->name;
                $order->customer_email = auth()->user()->email;
                $order->customer_phone = auth()->user()->phone;
                $order->invoice_number = $invoice_number;
                $order->order_type = 'ticket';
                $order->based_price = $based_price;
                $order->discount = $discount;
                $order->price = $price;
                $order->quantity = $quantity;
                $order->status = $status_tiket;
                $order->description = null;
                $order->voucher_code = $voucher_code;
                $order->save();

                if ($status_tiket == 0) {
                    $dataSend = [
                        'id' => $order->id,
                        'amount' => $order->price,
                        'product-desc' => 'evoria ticket',
                        'ticket_user_id' => $ticket->id,
                    ];

                    $paymentResponse = payToMidtrans($dataSend);

                    if(!empty($paymentResponse)){
                        if(!empty($paymentResponse["data"]) && !empty($paymentResponse["data"]["invoice-id"])){
                            Order::where('id', $dataSend['id'])->update([
                                'invoice_number' => $paymentResponse["data"]["invoice-id"],
                                'redirect_url' => $paymentResponse["data"]["redirect_url"],
                            ]);
                            TicketUser::where('id', $dataSend['ticket_user_id'])->update([
                                'invoice_number' => $paymentResponse["data"]["invoice-id"],
                            ]);
                        }

                        $data = $paymentResponse;
                    }
                }else {
                    if (isset($voucher)) {
                        $voucher = $voucher->update([
                            'redeemed_date' => date('y-m-d H:i:s'),
                            'user_apps_id' => auth()->user()->id,
                            'status' => 3
                        ]);
                    }
                }
            }else {
                $order_user = Order::where([ ['invoice_number', '=', $ticket_user->invoice_number] ])->first();

                if ($order_user && $order_user->redirect_url == '') {
                    $dataSend = [
                        'id' => $order_user->id,
                        'amount' => $order_user->price,
                        'product-desc' => 'evoria ticket',
                        'ticket_user_id' => $ticket_user->id,
                    ];

                    $paymentResponse = payToMidtrans($dataSend);

                    if(!empty($paymentResponse)){
                        if(!empty($paymentResponse["data"]) && !empty($paymentResponse["data"]["invoice-id"])){
                            Order::where('id', $dataSend['id'])->update([
                                'invoice_number' => $paymentResponse["data"]["invoice-id"],
                                'redirect_url' => $paymentResponse["data"]["redirect_url"],
                            ]);
                            TicketUser::where('id', $dataSend['ticket_user_id'])->update([
                                'invoice_number' => $paymentResponse["data"]["invoice-id"],
                            ]);
                        }

                        $data = $paymentResponse;
                    }
                }else {
                    $data = [
                        'data' => [
                            'redirect_url' => $order_user->redirect_url
                        ]
                    ];
                }



                $ticket_user->update([
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $order_user->update([
                    'created_at' => date('Y-m-d H:i:s')
                ]);

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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
