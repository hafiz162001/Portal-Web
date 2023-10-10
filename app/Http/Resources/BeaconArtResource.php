<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BeaconArtResource extends JsonResource
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
            'description' => $this->description,
            'img' => $this->galleries->map(function ($item, $key) {
                return asset('img/' . $item->image);
            }),
            'created_by' => $this->createdBy(),
            'created_at' => $this->created_at,
        ];
    }
}
