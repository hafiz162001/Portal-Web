<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MentoringResource;
use App\Http\Resources\StyleMusicResource;
use App\Http\Resources\TypeResource;

class ContestanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $icon_image = null;

        if ($request->category == 50) {
            $icon_image = $this->icon_evo50;
        }elseif ($request->category == 10) {
            $icon_image = $this->icon_band;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'biodata' => $this->biodata,
            // 'type_id' => $this->type_id,
            // 'type' => $this->type,
            'origin' => $this->origin,
            'status' => $this->status,
            // 'vote' => isset($this->counter) ? $this->counter : 0,
            'vote' => $this->likes_count,
            'jumlah_penampil' => $this->jumlah_penampil,
            'style_music' => empty($this->styleMusic) ? null : StyleMusicResource::collection($this->styleMusic),
            'type' => empty($this->typeContestan) ? null : TypeResource::collection($this->typeContestan),
            'address' => $this->address,
            'hubungan_pendaftar' => $this->hubungan_pendaftar,
            'link_social_media' => $this->link_social_media,
            'link_audo_video' => $this->link_audo_video,
            'nama_management' => $this->nama_management,
            'images' => empty($this->galleries) ? null : ( ($this->is_form == 0) ? $this->galleries->map(function($item, $key){
                return asset('img/' . $item->image);
            }) : $this->galleries->map(function($item, $key){
                return $item->image;
            }) ),
            'cover_images' => empty($this->coverGalleries) ? null : ( ($this->is_form == 0) ? $this->coverGalleries->map(function($item, $key){
                return asset('img/' . $item->image);
            }) : $this->coverGalleries->map(function($item, $key){
                return $item->image;
            }) ),
            'mentors' => empty($this->mentoring) ? null : MentoringResource::collection($this->mentoring),
            'music_count' => $this->music_count,
            'album_count' => $this->albums_count,
            'icon_band' => asset('img/' . $icon_image),
        ];

    }
}
