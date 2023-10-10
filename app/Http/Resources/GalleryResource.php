<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GalleryResource2;

class GalleryResource extends JsonResource
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
            'images' => asset('img/' . $this->image),
            'type' => $this->getType($this->image),
            'parent_id' => $this->parent_id,
            'thumbnail' => $this->thumbnail,
        ];
    }

    function getType($string){
        if (preg_match("/(\.jpg|\.png|\.bmp|\.jpeg)$/i", $string)) {
            return 'evoria_galleries';
        }else{
            return 'evoria_gallery_videos';
        }
    }
}
