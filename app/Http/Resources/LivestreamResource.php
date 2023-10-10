<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class LivestreamResource extends JsonResource
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
            'id'   => $this->id,
            'name' => $this->name,
            'link' => $this->getIdYoutube($this->link),
            'status' => $this->status,
            'status_label' => $this->status ? $this->getStatusLabel($this->status) : null,
            'user' => $this->user ? new UserResource($this->user) : null,
            'created_at' => date('d-M-Y H:i:s', strtotime($this->created_at)),
        ];
    }

    public function getIdYoutube($l){
        $link = null;

        if (str_contains($l, 'youtube.com')) {
            if (str_contains($l, 'live')) {
                $explodeLink = explode('live/', $l);
                $explodeLink2 = explode('?', $explodeLink[1]);

                if (!empty($explodeLink[0])) {
                    $link = $explodeLink2[0];
                }

            }

            if (str_contains($l, 'v=')) {
                $explodeLink = explode('v=', $l);
                if (!empty($explodeLink[1])) {
                    $explodeLink2 = explode('&', $explodeLink[1]);
                    $link = $explodeLink2[0];
                }
            }

            if (str_contains($link, 'shorts')) {
                $explodeLink = explode('shorts/', $link);

                if (!empty($explodeLink[1])) {
                    // if (str_contains($explodeLink[1], '?')) {
                    //     $explodeLink2 = explode('?', $explodeLink[1]);
                    //     if (!empty($explodeLink2[0])) {
                    //         $link = $explodeLink2[0];
                    //     }
                    // }else {
                        $link = $explodeLink[1];
                    // }
                }
            }

        }elseif (str_contains($l, 'youtu.be')) {
            $explodeLink = explode('youtu.be/', $l);
            if (!empty($explodeLink[1])) {
                $link = $explodeLink[1];
            }
        }

        return $link;
    }

    public function getStatusLabel($status = 0){
        $label[0] = 'End';
        $label[1] = 'Live';
        $label[2] = 'Unknown';

        return $label[$status];
    }
}
