<?php

namespace App\Http\Resources\User;

use App\Enum\OrderStatus;
use App\Enum\PaymentStatus;
use App\Http\Resources\OrderProductsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'total_amount' =>  $this->total_amount,
            'status' => OrderStatus::getValue($this->status),
            'payment_status' => PaymentStatus::getValue($this->payment_status),
            'created_at' => showDate($this->created_at),
            'products' => OrderProductsResource::collection($this->products)
        ];
    }
}
