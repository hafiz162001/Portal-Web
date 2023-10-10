<?php

namespace App\Http\Resources;
use App\Http\Resources\BlocLocationResource;
use App\Http\Resources\UserResource;

use Illuminate\Http\Resources\Json\JsonResource;

class UserActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // if (isset($this->gues_id)) {
        //     $this->user->merge(['gues_id' => $request->gues_id]);
        // }
        return [
            'id' => $this->id,
            'location' => !empty($this->blocLocation) ? new BlocLocationResource($this->blocLocation) : "Not Set",
            'user' => new UserResource($this->user),
            'beacon_checkin' => $this->beaconCheckin,
            'beacon_checkout' => $this->beaconCheckout,
            'checkin_at' => $this->checkin_at,
            'checkout_at' => $this->checkout_at
        ];
    }
}
