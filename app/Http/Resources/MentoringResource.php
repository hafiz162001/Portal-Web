<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MentorResource;

class MentoringResource extends JsonResource
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
            'mentor_id' => $this->mentor_id,
            'contestan_id' => $this->contestan_id,
            'name' => isset($this->detail->name) ? $this->detail->name : null,
            'images' => isset($this->detail->galleries) ? $this->detail->galleries->map(function($item, $key){
                return asset('img/' . $item->image);
            }) : null,
            // 'detail' => empty($this->detail) ? null : MentorResource::collection($this->detail),
        ];

    }
}
