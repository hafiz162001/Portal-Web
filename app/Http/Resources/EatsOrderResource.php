<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use \Carbon\Carbon;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;

class EatsOrderResource extends JsonResource
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
            'order_id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'cart' => new CartResource($this->cart),
            'total_price' => $this->total_price,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel($this->status),
        ];
    }

    function getStatusLabel($id = 0){
        $label[0] = 'Menunggu Pembayaran';
        $label[1] = 'Terbayar';
        $label[2] = 'Dibatalkan';
        $label[3] = 'Telah lewat';
        $label[4] = 'Di proses';
        $label[5] = 'Selesai';

        return $label[$id];
    }
}
