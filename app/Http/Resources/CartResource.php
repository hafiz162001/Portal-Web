<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Http\Requests\CartDetailRequest;
use App\Http\Resources\CartDetailResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (isset($request->cart_id)) {
            $location = null;
        }else {
            $location = new LocationResource($this->location);
        }

        return [
            'id' => $this->id,
            'cart_id' => $this->id,
            'user_app_id' => $this->user_app_id,
            'location' => new LocationResource($this->location),
            'nama_pelanggan' => $this->nama_pelanggan,
            'order_type' => $this->order_type,
            'order_type_label' => $this->getLabelOrderType($this->order_type),
            'cart_detail' => $this->details->map(function($item, $key){
                return new CartDetailResource($item);
            }),
            'seats_number' => $this->seats_number,
            // 'price' => $this->price,
            // 'description' => $this->description,
            // 'category' => $this->productCategory->name,
            // 'images' => $this->galleries->map(function($item, $key){
            //     return asset('img/' . $item->image);
            // }),
        ];

    }

    function getLabelOrderType($status = 0){
        $label[0] = 'Take Away';
        $label[1] = 'Dine In';

        return $label[$status];
    }
}
