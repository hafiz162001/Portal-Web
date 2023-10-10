<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ForumResource extends JsonResource
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
            'description' => $this->description,
            'image' => asset('img/' . $this->image),
            'cover_image' => asset('img/' . $this->cover_image),
            // 'topic' => $this->topic,
            'topic' => $this->topic->name,
            'count_threads' => $this->threads_count,
            'created_at' => date('Y m d H:i', strtotime($this->created_at)),
        ];
    }
}
