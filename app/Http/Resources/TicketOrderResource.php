<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \Carbon\Carbon;
class TicketOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $selectedDate = $this->selected_date;
        $eventStartTime = Carbon::create($this->eventTicket->event->time_from);
        $eventEndTime = Carbon::create($this->eventTicket->event->time_to);
        // $todayIsSelectedDate = Carbon::today() == Carbon::create($selectedDate);
        $todayIsSelectedDate = date('Y-m-d') == date('Y-m-d', strtotime($selectedDate));
        // $nowIsBetweenTimes = Carbon::now()->between($eventStartTime, $eventEndTime);
        // $isRedeemable = $todayIsSelectedDate && $nowIsBetweenTimes && !$this->isUsed && $this->status == 1;
        $isRedeemable = $todayIsSelectedDate && !$this->isUsed && $this->status == 1;
        return [
            'id' => $this->id,
            'ticket' => new TicketResource($this->eventTicket),
            'redeemableNow' => $isRedeemable,
            'user' => $this->user,
            'visitor' => $this->visitor,
            'selected_date' => $this->selected_date,
            'amount' => $this->amount,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'total_price' => $this->total_price,
            'invoice_id' => $this->invoice_id,
            'createdBy' => $this->createdBy,
            'created_at' => $this->created_at,
            'isUsed' => $this->isUsed,
            'qrValue' => $this->qrValue,
        ];
    }
}
