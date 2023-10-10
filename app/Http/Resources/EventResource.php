<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use Laravolt\Indonesia\Models\Village;
use \Carbon\Carbon;
class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $village = Village::with(['district', 'district.city', 'district.city.province'])->find($this->indonesia_village_id);
        $dateIsSame = $this->date_from == $this->date_to;
        $formattedDate = explode(" ", Carbon::create($this->date_from)->format('d M Y'))[0] . " - " . Carbon::create($this->date_to)->format('d M Y');
        if($dateIsSame){
            $formattedDate = Carbon::create($this->date_from)->format('d M Y');
        }
        return [
            'id' => $this->id,
            'image' => asset('img/' . $this->image),
            'order' => $this->order,
            'title' => $this->title,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'formatted_date' => $formattedDate,
            'time' => date('d', strtotime($this->time_from)).' - '.date('d M Y', strtotime($this->time_to)),
            'description' => $this->description,
            'location' => $this->location,
            'coordinate' => $this->coordinate,
            'location_detail' => $this->location_detail,
            'return_terms' => $this->return_terms,
            'term_and_condition' => $this->term_and_condition,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => User::find($this->created_by),
            'link' => $this->link,
            'tickets' => $this->whenLoaded('tickets'),
            // 'lowest_price' => !empty($this->tickets->sortBy('price')) ? $this->tickets->sortBy('price')[0] : "0",
            'lowest_price' => !empty($this->tickets->sortBy('price')->first()) ? $this->tickets->sortBy('price')->first() : null,
            'is_past_event' => $this->checkEvent($this->date_to),
        ];
    }

    function checkEvent($tgl){
        $is_past_event = 0;
        if ($tgl < date('Y-m-d')) {
            $is_past_event = 1;
        }

        return $is_past_event;
    }
}
