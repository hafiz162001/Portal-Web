<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'ppn' => $this->ppn,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'isRefundable' => $this->isRefundable,
            'created_at' => $this->created_at,
            'type' => $this->type,
            'event' => new EventResource($this->whenLoaded('event')),
        ];
    }
}
