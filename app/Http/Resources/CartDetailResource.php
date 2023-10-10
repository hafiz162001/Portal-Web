<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;

class CartDetailResource extends JsonResource
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
            'cart_id' => $this->cart_id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'catatan' => $this->product_description,
            'amount'  => number_format(($this->product_based_price), 0, ",", "."),
            'discount'  => number_format(($this->product_discount), 0, ",", "."),
            'quantity'=> $this->quantity,
            'images' => asset('img/' . $this->product_image)
            // 'location' => new LocationResource($this->location->id),
            // 'price' => $this->price,
            // 'description' => $this->description,
            // 'category' => $this->productCategory->name,
            // 'images' => $this->galleries->map(function($item, $key){
            //     return asset('img/' . $item->image);
            // }),
        ];
    }
}
