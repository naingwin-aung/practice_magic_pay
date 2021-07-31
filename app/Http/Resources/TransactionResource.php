<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $type = '';
        $title = '';
        if($this->type == 1) {
            $title = 'From - ' . ($this->source ? $this->source->name : '');
            $type = '+';
        }else if($this->type == 2) {
            $title = 'To - ' .  ($this->source ? $this->source->name : '');
            $type = '-';
        }
        return [
            'trx_id' => $this->trx_id,
            'amount' => $type . number_format($this->amount, 2) . ' MMK',
            'type' => $this->type, //1 => income, 2 => expense
            'source' => $title,
            'date_time' => Carbon::parse($this->created_at)->diffForHumans() . ' - ' . Carbon::parse($this->created_at)->toFormattedDateString() . ' - ' . Carbon::parse($this->created_at)->format('H:i:s'),
        ];
    }
}
