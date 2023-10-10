<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\ScheduleResource;

class FestivalScheduleResource extends JsonResource
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
            'schedule_id' => $this->id,
            'event_id' => $this->event_id,
            'name' => $this->name,
            'description' => $this->description,
            'date' => date('l, d M Y', strtotime($this->festival_schedule_date_start)),
            'time' => $this->festival_schedule_time_start.' - '.$this->festival_schedule_time_end,
            'file' => asset('img/' . $this->file),
            'thumbail' => asset('img/' . $this->thumbnail),
            'location' => empty($this->location) ? null : new LocationResource($this->location),

            // 'schedule' => $this->schedule->map(function($item, $key){
            //     return new ScheduleResource($item);
            // }),
        ];

    }
}
