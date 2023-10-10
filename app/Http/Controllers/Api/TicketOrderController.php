<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TicketOrder;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TicketOrderResource;
use App\Models\EventTicket;
use App\Models\Notification;
use App\Models\Visitor;
use App\Models\TicketOrderLog;
use Kutia\Larafirebase\Facades\Larafirebase;
use \Carbon\Carbon;

class TicketOrderController extends Controller
{
    public function index(Request $request)
    {
        $success = true;
        $data = [];
        $msg = null;
        try {
            $wishlist = TicketOrder::with(['eventTicket', 'eventTicket.event', 'user', 'visitor', 'createdBy'])->orderBy('id', 'desc')->where('created_by', auth()->user()->id);
            if(!empty($request->isUsed) && $request->isUsed == 1){
                $wishlist->where("isUsed", true);
            }
            $wishlist = $wishlist->paginate(10);
            $data = TicketOrderResource::collection($wishlist);
        } catch (\Throwable $th) {
            $success = false;
            // $msg = $th->getMessage();
            $msg = config('app.error_message');
        }
        $res = [
            'success' => $success,
            'data'    => $data,
            'message' => $msg,
            'nextUrl' => !$success ? null : $data->withQueryString()->nextPageUrl(),
            'prevUrl' => !$success ? null : $data->withQueryString()->previousPageUrl(),
        ];
        return $res;
    }

    public function redeem(Request $request){
        $success = true;
        $data = [];
        $msg = null;
        try {
            Validator::make($request->all(), [
                'ticket_order_id' => 'required|exists:ticket_orders,id',
            ])->validate();
            $hasTicket = TicketOrder::with(['eventTicket', 'eventTicket.event'])
            ->where('id', $request->ticket_order_id)
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

    public function create(Request $request){
        $success = true;
        $data = null;
        $msg = null;
        $paymentResponse = null;
        try {
            Validator::make($request->all(), [
                'event_ticket_id' => 'required|exists:event_tickets,id',
                'selected_date' => 'required',
                'amount' => 'required',
            ])->validate();
            $ticket = EventTicket::find($request->event_ticket_id);
            if(empty($ticket)){
                throw new \Exception("Ticket not found");
            }
            $amount = $request->amount;
            if(empty($amount)){
                $amount = 1;
            }
            $totalPrice = $amount * $ticket->total;
            $visitorId = null;
            if(!empty($request->visitor) && count($request->visitor) > 0){
                foreach ($request->visitor as $key => $visitor) {
                    Validator::make($visitor, [
                        'phone' => 'required',
                        'email' => 'required',
                        'title' => 'required',
                        'name' => 'required',
                        'ktp' => 'required',
                    ])->validate();
                    $visitorData = Visitor::updateOrCreate([
                        'created_by'   => auth()->user()->id,
                        'phone'   => $visitor['phone'],
                        'email'   => $visitor['email'],
                    ],[
                        'title'    => $visitor['title'],
                        'name'    => $visitor['name'],
                        'ktp'    => $visitor['ktp'],
                    ]);
                    if($visitorData) {
                        $visitorId = $visitorData->id;
                    }
                }
            }
            $data = TicketOrder::Create([
                'user_id'   => auth()->user()->id,
                'event_ticket_id'   => $request->event_ticket_id,
                'selected_date'   => $request->selected_date,
                'amount'    => $request->amount,
                'visitor_id'    => $visitorId,
                'total_price'    => $totalPrice,
                'status' => ($totalPrice == 0) ? 1 : 0,
            ]);
            if($data){
                if($totalPrice > 0){
                    $body = collect($data)->merge([
                        'product-desc' => 'ticket',
                        'amount' => $totalPrice,
                    ]);
                    $paymentResponse = payToMidtrans($body);
                    if(!empty($paymentResponse)){
                        // $ticketOrderLog = TicketOrderLog::create([
                        //     'ticket_order_id' => $data->id,
                        //     'request' => $paymentResponse["request"],
                        //     'response' => $paymentResponse["data"]
                        // ]);
                        if(!empty($paymentResponse["data"]) && !empty($paymentResponse["data"]["invoice-id"])){
                            $data->update([
                                'invoice_id' => $paymentResponse["data"]["invoice-id"]
                            ]);
                        }
                    }
                }
                if($data->status == 1){
                    $desc = 'Kamu telah berhasil membeli ticket '. $data->eventTicket->name . ' untuk event ' . $data->eventTicket->event->title;
                    if(!empty($data->invoice_id)){
                        $desc = $desc . " dengan nomor invoice " . $data->invoice_id;
                    }
                    $notification = Notification::create([
                        'type' => 'general',
                        'title' => 'Pembayaran berhasil',
                        'description' => $desc,
                    ]);
                    $fcmTokens = auth()->user()->fcm_token;
                    if($notification && !empty($fcm_token)){
                        if(!empty($fcmTokens)){
                            $notif = Larafirebase::withTitle($notification->title)
                            ->withBody($notification->description)
                            ->withSound('default')
                            ->withPriority('high')
                            ->sendNotification($fcmTokens);
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            $success = false;
            $msg = $th->getMessage();
        }
        $res = [
            'request' => $request->all(),
            'success' => $success,
            'data'    => !empty($data) ? new TicketOrderResource($data) : $data,
            'message' => $msg,
            'paymentResponse' => $paymentResponse,
        ];
        return $res;
    }
}
