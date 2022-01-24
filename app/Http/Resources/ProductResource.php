<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductImageResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'title' => $this->title,
            'size' => $this->size,
            'gender' => $this->gender,
            'mark' => $this->mark,
            'price' => $this->price,
            'category' => Category::findOrFail($this->category_id),
            'image_url' => ProductImageResource::collection($this->productImages),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
