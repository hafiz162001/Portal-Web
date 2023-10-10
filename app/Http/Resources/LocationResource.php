<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BlocLocationResource;
use App\Http\Resources\MerchResource;
class LocationResource extends JsonResource
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
            'type' => $this->type,
            'time_from' => $this->formatedTime($this->time_from),
            'time_to' => $this->formatedTime($this->time_to),
            'phone' => $this->phone,
            'title' => $this->title,
            'description' => strip_tags($this->description),
            'category' => $this->category,
            'bloc' => !empty($this->blocLocation) ? new BlocLocationResource($this->blocLocation) : null,
            'images' => $this->galleries->map(function($item, $key){
                return asset('img/' . $item->image);
            }),
            'products' => empty($this->products) ? null : MerchResource::collection($this->products),
            'venues_image' => $this->galleries_venues->map(function($item, $key){
                return asset('img/' . $item->image);
            }),
            'experience_image' => $this->galleries_experience->map(function($item, $key){
                return asset('img/' . $item->image);
            }),
            'entrance' => $this->entrance,
            'isLike' => $this->isLike(),
            'max_people' => $this->max_people,
            'axis' => $this->axis,
            'yaxis' => $this->yaxis,
            'ig' => $this->ig,
        ];
    }

    function formatedTime($time){
        $time = explode(':', $time);
        $formatedTime = $time[0].':'.$time[1];
        return $formatedTime;
    }
}
