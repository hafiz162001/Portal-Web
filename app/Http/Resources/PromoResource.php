<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \Carbon\Carbon;
class PromoResource extends JsonResource
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
            'image' => asset('img/' . $this->image),
            'order' => $this->order,
            'title' => $this->title,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'formatted_date' => explode(" ", Carbon::create($this->date_from)->format('d M Y'))[0] . " - " . Carbon::create($this->date_to)->format('d M Y'),
            'period_of_use' => $this->period_of_use,
            'usage' => $this->getUsage(),
            'description' => $this->description,
            'term_and_condition' => $this->term_and_condition,
            'codes' => $this->codes
        ];
    }
}
