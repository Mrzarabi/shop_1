<?php

namespace App\Http\Resources\Api\V1\Product;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
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
                    'title' => $item->title,
                    'desc' => $item->desc,
                    // 'body' => $item->body,
                    'u_price' => $item->u_price,
                    'c_price' => $item->c_price,
                    'inventory' => $item->inventory,
                ];
            })
        ];
    }
}