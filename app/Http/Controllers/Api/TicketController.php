<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventTicket;
use Illuminate\Support\Facades\Validator;
class TicketController extends Controller
{
    public function calculate(Request $request)
    {
        $success = true;
        $data = [];
        $msg = null;
        try {
            Validator::make($request->all(), [
                'ticket_id' => 'required',
                'amount' => 'required',
            ])->validate();
            $ticket = EventTicket::find($request->ticket_id);
            $data = [
                'total' => $ticket->total * $request->amount,
                'subtotal' => $ticket->subtotal * $request->amount,
                'ppn' => $ticket->ppn * $request->amount,
            ];
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
}
