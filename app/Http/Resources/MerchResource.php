<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MerchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $label = $this->price;

        if ($this->stock == 0) {
            $label = 'Sold Out';
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $label,
            'discount' => $this->discount_amount,
            'total_price' => $label,
            'category' => $this->productCategory->name,
            'description' => $this->description,
            'images' => $this->galleries->map(function($item, $key){
                return asset('img/' . $item->image);
            }),
            'is_like' => !empty($this->isLike()),
            'total_like' => $this->totalLike(),
            'stock' => $this->stock
        ];

        // $data[$this->productCategory->name] = [
        //     'id' => $this->id,
        //     'name' => $this->name,
        //     'price' => $this->price,
        //     'total_price' => ($this->price - $this->amount_discount),
        //     'category' => $this->productCategory->name,
        //     'description' => $this->description,
        //     'images' => $this->galleries->map(function($item, $key){
        //         return asset('img/' . $item->image);
        //     }),
        // ];
        // return $data;
    }
}
