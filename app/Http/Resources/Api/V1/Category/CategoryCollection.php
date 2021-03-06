<?php

namespace App\Http\Resources\Api\V1\Category;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
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
            'data' => $this->collection->map(function($item) {
                return [
                    'id'    => $item->id,
                    'category_id' => $item->category_id,
                    'title' => $item->title,
                    // 'desc' => $item->desc,
                    // 'image' => $item->image,
                    'children' => new SubCategoryCollection($item->categories),
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'status' => 'success'
        ];
    }
}
