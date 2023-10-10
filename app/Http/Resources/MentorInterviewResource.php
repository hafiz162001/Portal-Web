<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MentorResource;

class MentorInterviewResource extends JsonResource
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
            'mentor_id' => $this->mentor_id,
            'name' => $this->name,
            'duration' => $this->duration,
            'description' => $this->description,
            'total_views' => $this->views,
            'total_likes' => $this->mi_like_count,
            'created_at'  => date('d-m-Y H:i:s', strtotime($this->created_at)),
            'mentor'      => $this->mentor ? new MentorResource($this->mentor) : null,
            'thumbnail' => $this->thumbnail->map(function($item, $key){
                return asset('img/' . $item->image);
            }),
            'videos' => $this->videos->map(function($item, $key){
                return $item->image;
            }),
            'id_youtube' => $this->videos->map(function($item, $key){
                return $this->getIdYoutube($item->image);
            }),
            'is_liked' => $this->liked_count == 1 ? true : false,
            'is_saved' => $this->is_saved_count == 1 ? true : false,
        ];

    }

    function getIdYoutube($link){
        if (str_contains($link, 'www.youtube.com')) {
            if(str_contains($link, 'v=')){
                $explodeLink = explode('v=', $link);
                $explodeLink2 = explode('&', $explodeLink[1]);
    
                return $explodeLink2[0];
            }else{
                $explodeLink = explode('youtube.com/', $link);
                $explodeLink2 = explode('&', $explodeLink[1]);
    
                return $explodeLink2[0];
            }
        }else {
            return null;
        }

    }
}
