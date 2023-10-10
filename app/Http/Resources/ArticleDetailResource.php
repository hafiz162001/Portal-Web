<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleDetailResource extends JsonResource
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
            'articles_id' => $this->articles_id,
            'description' => $this->description,
            'caption' => $this->caption,
            'status' => $this->status,
            'images' => $this->galleries->map(function($item, $key){
                return asset('img/' . $item->image);
            }),
            'url' => $this->getShowCaseDataType($this->url),
            'url_type' => $this->getShowCaseDataType($this->url, 1),
        ];

    }

    function getShowCaseDataType($link, $x = 0){

        if (str_contains($link, 'youtube.com')) {
            if (str_contains($link, 'shorts')) {
                $explodeLink = explode('shorts/', $link);

                if (!empty($explodeLink[1])) {

                    if ($x == 1) {
                        return 'youtube';
                    }else {
                        return $explodeLink[1];
                    }

                }
            }else {
                $explodeLink = explode('v=', $link);

                if (!empty($explodeLink[1])) {
                    $explodeLink2 = explode('&', $explodeLink[1]);

                    if ($x == 1) {
                        return 'youtube';
                    }else {
                        return $explodeLink2[0];
                    }

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
