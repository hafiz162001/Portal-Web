<?php

namespace App\Http\Resources;

use App\Http\Requests\LocationRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LocationResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'price' => $this->price,
            'discount' => $this->discount_amount,
            'total_price' => ($this->price - $this->discount_amount),
            'description' => $this->description,
            'location' => new LocationResource($this->location),
            'category' => $this->productCategory->name,
            'images' => $this->galleries->map(function($item, $key){
                return asset('img/' . $item->image);
            }),
            'is_like' => !empty($this->isLike()),
        ];
    }
}
