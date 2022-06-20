<?php

namespace App\Http\Resources;

use App\Models\Purchase;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'user' => UserResource::make($this->user),
            'supplier_id' => $this->supplier_id,
            'title' => $this->title,
            'remark' => $this->remark,
            'status' => Purchase::PURCHASE_STATUS_GROUP[$this->status],
            'deadline_at' => $this->deadline_at,
            'complete_at' => $this->complete_at,
            'created_at' => $this->created_at,
            'items' => PurchaseItemsResource::collection($this->items),
            'editUrl' => adminRoute('purchase.edit', $this->id),
            'delUrl' => adminRoute('purchase.destroy', $this->id)
        ];
    }
}
