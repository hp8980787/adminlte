<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseCollnection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "data"=>PurchaseResource::collection($this->collection),
            "links" => [
                "first" => $this->url(1),
                "last" => $this->url($this->lastPage()),
                "prev" => $this->previousPageUrl(),
                "next" => $this->nextPageUrl()
            ],
            "meta" => [
                "currentPage" => $this->currentPage(),
                "from" => 1,
                "lastPage" => $this->lastPage(),
                "path" => $request->url(),
                "perPage" => $this->perPage(),
                "total" => $this->total(),
            ],
        ];
    }
}
