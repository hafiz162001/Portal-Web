<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'images' => asset('img/' . $this->image),
            'status' => $this->status,
            'status_label' => $this->getStatusLabel($this->status)
        ];
    }

    function getStatusLabel($status = 0){
        $label[0] = 'Draft';
        $label[1] = 'Send';
        $label[2] = 'Opened';

        return $label[$status];
    }
}
