<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PricingResource;

class EvoriaTicketResource extends JsonResource
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
            'images' => $this->galleries->map(function($item, $key){
                return asset('img/' . $item->image);
            }),
            'status' => $this->status,
            'title' => $this->title,
            'description' => $this->description,
            'started_date' => $this->started_date,
            'finished_date' => $this->finished_date,
            'formated_date' => date('d D', strtotime($this->started_date)).' - '.date('d D', strtotime($this->finished_date)),
            'formated_time' => date('M Y', strtotime($this->finished_date)),
            'term_and_conditions' => $this->term_and_conditions,
            'pricing' => !empty($this->pricing) ? PricingResource::collection($this->pricing) : null,
        ];
    }
}
