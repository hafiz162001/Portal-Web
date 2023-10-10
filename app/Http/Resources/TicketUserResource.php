<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EvoriaTicketResource;
use Illuminate\Support\Facades\Hash;

class TicketUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_apps_id' => $this->user_apps_id,
            'ticket_id' => $this->ticket_id,
            'pricing_id' => $this->pricing_id,
            'invoice_number' => $this->invoice_number,
            'code' => $this->ticket_code,
            'ticket_code' => md5($this->ticket_code),
            'based_price' => $this->based_price,
            'discount' => $this->discount,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'ticket_type' => $this->ticket_type,
            'selected_date' => $this->selected_date,
            'status' => $this->status,
            'status_label' => $this->get_status_label($this->status),
            'description' => $this->description,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'ticket' => !empty($this->ticket) ? new EvoriaTicketResource($this->ticket) : null,
            'order_status' => !empty($this->order) ? $this->get_status_order($this->order->status, $this->order->created_at) : null,
            'order_status_label' => !empty($this->order) ? $this->get_status_order_label($this->get_status_order($this->order->status, $this->order->created_at)) : null,
            'order_redirect_url' => !empty($this->order) ? $this->order->redirect_url : null,
            'barcode_image' => asset('img/' . 'gambar1.jpeg'),
        ];
    }

    function get_status_label($val = 0){
        $label[0] = 'Submited';
        $label[1] = 'Active';
        $label[2] = 'Chekin';
        $label[3] = 'Chekout';

        return $label[$val];
    }

    function get_status_order($status, $created_at){
        $time = date('Y-m-d H:i:s', strtotime($created_at));
        $timePlusFifteen = date('Y-m-d H:i:s', strtotime("+5 minutes", strtotime($created_at)));
        if ($status == 0 && date('Y-m-d H:i:s') >= $timePlusFifteen) {
            return 3;
        }else {
            return $status;
        }
    }

    function get_status_order_label($val = 0){
        $label[0] = 'Menunggu Pembayaran';
        $label[1] = 'Terbayar';
        $label[2] = 'Gagal';
        $label[3] = 'Kadaluarsa';

        return $label[$val];
    }
}
