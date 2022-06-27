<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'jianjie1' => $this->jianjie1,
            'jianjie2' => $this->jianjie2,
            'category' => $this->category,
            'brand' => $this->brand,
            'cover_img' => $this->cover_img,
            'imgs' => $this->imgs,
            'dl' => $this->dl,
            'dy' => $this->dy,
            'type' => $this->type,
            'size' => $this->size,
            'bzq' => $this->bzq,
            'price_eu' => $this->price_eu,
            'price_us' => $this->price_us,
            'price_uk' => $this->price_uk,
            'price_jp' => $this->price_jp,
            'statue' => $this->status,
            'replace' => $this->replace,
            'description' => $this->description,
            'stock' => $this->stock,
            'editUrl' => adminRoute('products.edit', [$this->id]),
            'delUrl' => adminRoute('products.destroy', [$this->id]),
            'data' => $this->created_at
        ];
    }
}
