<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class ThreadResource extends JsonResource
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
            'forum_id' => $this->forum_id,
            'title' => $this->title,
            'message'   => $this->message,
            'type'  => $this->type,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
            'link_poto_video' => str_contains($this->link_poto_video, 'http') ?  $this->link_poto_video : asset('img/' . $this->link_poto_video),
            'likes_count'   => $this->likes_count,
            'messages_count' => $this->messages_count,
            'views_count'  => $this->views_count,
            'user' => $this->user ? new UserResource($this->user) : null,
            'is_liked' => $this->is_liked ? true : false,
            // 'created_at' => date('Y m d H:i', strtotime($this->created_at)),
        ];
    }
}
