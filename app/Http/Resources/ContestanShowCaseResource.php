<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContestanResource;
use App\Http\Resources\UserResource;

class ContestanShowCaseResource extends JsonResource
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
            // 'counter'          => $this->counter,
            'id'               => $this->id,
            'contestan_id'     => $this->contestan_id,
            'user_apps_id'     => $this->user_apps_id,
            'album_id'         => $this->album_id,
            'title'            => $this->title,
            'singer'           => $this->singer,
            'writen'           => $this->writen,
            'produce'          => $this->produce,
            'description'      => $this->description,
            'type'             => $this->type,
            'status'           => $this->status,
            'status_label'     => $this->getStatusLabel($this->status),
            // 'num_of_partition' => $this->num_of_partition,
            'music'            => empty($this->music) ? null : $this->music->map(function($item, $key){
                return $item->file;
            }),
            'id_youtube_music' => empty($this->music) ? null : $this->music->map(function($item, $key){
                return $this->getIdYoutube($item->file);
            }),
            'video'            => empty($this->video) ? null : $this->video->map(function($item, $key){
                return $item->file;
            }),
            'id_youtube_video' => empty($this->video) ? null : $this->video->map(function($item, $key){
                return $this->getIdYoutube($item->file);
            }),
            'show_case_data'   => empty($this->showCaseData) ? null : $this->showCaseData->map(function($item, $key){
                return $this->getShowCaseDataType($item->file);
            }),
            'show_case_data_type' => empty($this->showCaseData) ? null : $this->showCaseData->map(function($item, $key){
                return $this->getShowCaseDataType($item->file, 1);
            }),
            'cover'            => $this->cover ? asset('data/' . $this->cover) : null,
            'contestan'        => $this->contestan ? new ContestanResource($this->contestan) : null,
            'user'             => $this->userApp ? new UserResource($this->userApp) : null,
            'is_liked'         => $this->is_liked ? true : false,
            'is_saved'         => $this->is_saved ? true : false,
            'total_like'       => $this->liked_count,
            'total_comment'    => $this->comment_count,
        ];
    }

    public function getStatusLabel($status=0){
        $label[0] = 'Submited';
        $label[1] = 'Aproved';
        $label[2] = 'Declined';

        return $label[$status];
    }

    function getIdYoutube($link){
        if (str_contains($link, 'www.youtube.com')) {
            $explodeLink = explode('v=', $link);
            if (!empty($explodeLink[1])) {
                $explodeLink2 = explode('&', $explodeLink[1]);
                return $explodeLink2[0];
            }
        }
        return null;
    }

    function getShowCaseDataType($link, $x = 0){

        if (str_contains($link, 'youtube.com')) {
            $explodeLink = explode('v=', $link);

            if (!empty($explodeLink[1])) {
                $explodeLink2 = explode('&', $explodeLink[1]);

                if ($x == 1) {
                    return 'youtube';
                }else {
                    return $explodeLink2[0];
                }

            }
        }elseif (str_contains($link, 'youtu.be')) {
            $explodeLink = explode('youtu.be/', $link);

            if (!empty($explodeLink[1])) {

                if ($x == 1) {
                    return 'youtube';
                }else {
                    return $explodeLink[1];
                }

            }
        }elseif (str_contains($link, '.mp4')) {
            if ($x == 1) {
                return 'video';
            }else {
                return $link;
            }
        }else {
            if ($x == 1) {
                return 'webview';
            }else {
                return $link;
            }
        }

        return null;
    }
}
