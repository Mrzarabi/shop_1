<?php

namespace App\Http\Resources\Api\V1\Ticket;

use App\Models\Ticket;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TicketCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map( function($item) {
                return [
                    'id'    => $item->id,
                    'name' => $item->name,
                    'email' => $item->email,
                    'phone_number' => $item->phone_number,
                    'image' => $item->image,
                    'title' => $item->title,
                    'body' => $item->body,
                    'status' => $item->stauts,
                    'time' => jdate($item->created_at)->format('%B %d، %Y'),
                    'answer' => new TicketCollection(Ticket::where('ticket_id', $item->id)->get())
                ];
            })
        ];
    }
}
