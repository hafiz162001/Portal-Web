<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class MentorResource extends JsonResource
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
            'description' => $this->description,
            'images' => $this->galleries->map(function($item, $key){
                return asset('img/' . $item->image);
            }),
            'profile_images' => $this->galleriesProfile->map(function($item, $key){
                return asset('img/' . $item->image);
            }),
        ];

    }
}
