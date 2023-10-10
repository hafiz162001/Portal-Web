<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\ContestanResource;

class AlbumResource extends JsonResource
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
            'id'           => $this->id,
            'user_apps_id' => $this->user_apps_id,
            'contestan_id' => $this->contestan_id,
            'name'         => $this->name,
            'year'         => $this->year,
            'description'  => $this->description,
            'images'       => $this->galleries->map(function($item, $key){
                return asset('img/' . $item->image);
            }),
            'user' => $this->user ? new UserResource($this->user) : null,
            'contestan' => $this->contestan ? new ContestanResource($this->contestan) : null,
        ];
    }
}
