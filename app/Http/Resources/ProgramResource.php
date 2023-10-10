<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DescriptionResource;

class ProgramResource extends JsonResource
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
            'official' => $this->official,
            'start_date' => $this->start_date ? date('d M Y', strtotime($this->start_date)) : null,
            'end_date' => $this->end_date ? date('d M Y', strtotime($this->end_date)) : null,
            'images' => $this->galleries->map(function($item, $key){
                return asset('img/' . $item->image);
            }),
            'descriptions2' => !empty($this->descriptions) ? DescriptionResource::collection($this->descriptions) : null,
        ];

        // return parent::toArray($request);
    }
}
