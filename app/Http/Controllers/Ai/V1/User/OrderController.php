<?php

namespace App\Http\Controllers\Ai\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateOrderRequest;
use App\Http\Requests\User\UpdatePaymentStatus;
use App\Services\User\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderRequest $request){
        return $this->orderService->createOrder($request->products);
    }

    public function updatePaymentStatus(UpdatePaymentStatus $request){
        return $this->orderService->updatePaymentStatus($request->order_id,$request->status);
    }

}
