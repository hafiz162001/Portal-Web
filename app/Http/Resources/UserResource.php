<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (isset($this->gues_id) && $this->gues_id != 0) {
            $activity = $this->guesActivities->first();
        }else {
            $activity = !empty($activity->activities) ? $this->activities->first() : null;
        }

        $blocLocation = null;
        if(!empty($activity)){
            $blocLocation = $activity->blocLocation;
        }
        return [
            'id' => $this->id,
            'name' => $this->name ?? "User",
            'dob' => $this->dob,
            'ktp' => $this->ktp,
            'title' => $this->title,
            'gender' => $this->gender,
            'email' => $this->email,
            'phone' => $this->phone,
            'isCheckin' => isset($this->gues_id) ? $this->guesCheckinStatus($this->gues_id) : $this->isCheckin,
            'isRegistered' => $this->isRegistered,
            'isGuest' => $this->isGuest,
            'role' => $this->getRole($this->role),
            'foto' => !empty($this->foto) ? asset('img/' . $this->foto) : "-",
            'foto_sampul' => !empty($this->foto_sampul) ? asset('img/' . $this->foto_sampul) : "-",
            'activityInfo' => [
                'checkinDate' => (!empty($activity) && !empty($activity->checkin_at)) ? Carbon::parse($activity->checkin_at)->format('d F Y') : "-",
                'checkinTime' => (!empty($activity) && !empty($activity->checkin_at)) ? Carbon::parse($activity->checkin_at)->format('H:i') : "-",
                'checkoutTime' => (!empty($activity) && !empty($activity->checkout_at)) ? Carbon::parse($activity->checkout_at)->format('H:i') : "-",
                'location' => (!empty($blocLocation)) ? new BlocLocationResource($blocLocation) : null,
                'blocx' => [
                    'label' => 'BLOCX',
                    'icon'  => asset('/img/mbloc-logo@2x.png'),
                ],
                'x-colaborator' => [
                    'label' => 'X COLLABORATOR',
                    'icon'  => asset('/img/alternative-1.png'),
                ],
            ],
            'activeEvent' => $this->event,
            'member_right' => $this->member_right,
            'user_uuid' => $this->user_uuid,
            'is_user' => ($this->contestan ? 1 : $this->name ) ? 1 : 0,
            'is_contestan' => $this->contestan ? 1 : 0,
            'is_pendaftar' => $this->pendaftar ? 1 : 0,
            'gues_id' => $this->gues_id,
            'fcm_token' => $this->fcm_token
            // 'gues_act' => $this->guesActivities,
        ];
    }

    function guesCheckinStatus($id){
        $gues = DB::table('gues_fcm_token')->where('id', $id)->first();
        if ($gues) {
            return $gues->is_chekin;
        }else {
            return false;
        }
    }
}
