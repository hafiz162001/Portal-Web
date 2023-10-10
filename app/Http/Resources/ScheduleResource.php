<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            'name' => date('Y-m-d', strtotime($this->festival_schedule_date_start)) == date('Y-m-d') ? 'Hari ini' : date('d M', strtotime($this->festival_schedule_date_start)),
            'values' => $this->festival_schedule_date_start,
        ];
    }
}
