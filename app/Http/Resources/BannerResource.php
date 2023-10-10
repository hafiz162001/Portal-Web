<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
class BannerResource extends JsonResource
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
            'file' => asset('img/' . $this->file),
            'order' => $this->order,
            'link_detail' => $this->link_detail,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => User::find($this->created_by)->name
        ];
    }
}
