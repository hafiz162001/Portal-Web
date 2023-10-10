<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PricingResource extends JsonResource
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
            'title' => $this->title,
            'price' => $this->price,
            'quota' => $this->quota,
            'description' => $this->description,
            'pricing_type' => $this->pricing_type,
            'pricing_type_label' => $this->pricing_type ? $this->get_label($this->pricing_type) : null,
            'parent_id' => $this->parent_id,
            'category' => $this->category,
            'type' => $this->type,
        ];
    }

    function get_label($val = 1){
        $label[1] = 'Bisa Refund';
        $label[2] = 'Tidak Bisa Refund';

        return $label[$val];
    }
}
