<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class CommentResource extends JsonResource
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
            'user' => $this->user ? new UserResource($this->user) : null,
            'message' => $this->message,
            'parent_id' => $this->parent_id,
            'category' => $this->category,
            'type' => $this->type,
            'created_at' => date('d M Y H:i:s', strtotime($this->created_at)),
            'another_comment_count' => $this->another_comment_count ? $this->another_comment_count : 0,
            'like_comment_count' => $this->like_comment_count ? $this->like_comment_count : 0,
            'is_liked' => $this->is_liked_count == 1 ? true : false,
        ];
    }
}
