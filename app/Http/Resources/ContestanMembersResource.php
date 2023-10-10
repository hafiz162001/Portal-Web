<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContestanMembersResource extends JsonResource
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
            'contestan_id' => $this->contestan_id,
            'user_app_id' => $this->user_app_id,
            'phone' => $this->phone,
            'nama_pelanggan' => $this->nama_pelanggan,
            'jabatan' => $this->jabatan,
            'status' => $this->status,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel($this->status),
        ];
    }

    function getStatusLabel($status = 0){
        $label[0] = 'Requested';
        $label[1] = 'Approved';
        $label[2] = 'Rejected';

        return $label[$status];
    }
}
