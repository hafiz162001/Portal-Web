<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThreadMessageResource extends JsonResource
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
            'forum_thread_id' => $this->forum_thread_id,
            'user_apps_id' => $this->user_apps_id,
            'message' => $this->message,
            'user' => $this->user ? new UserResource($this->user) : null,
            'created_at' => date('d-m-Y H:i:s', strtotime($this->created_at))
        ];
    }
}
