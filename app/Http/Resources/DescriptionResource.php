<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EvoriaTicketResource;

class DescriptionResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'type' => $this->type,
            'description' => $this->descriptions,
            'image' => $this->image ? asset('img/' . $this->image) : null,
            'ticket_id' => $this->ticket_id,
            'ticket' => !empty($this->tickets) ? EvoriaTicketResource::collection($this->tickets) : null,
        ];

        return parent::toArray($request);
    }
}
