<?php

namespace App\Services\User;

use App\Http\Resources\User\OrderResource;
use App\Repositories\User\OrderRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\Log;

class OrderService extends BaseService
{
    public function __construct(protected OrderRepository $orderRepository)
    {
    }


    public function createOrder(array $products)
    {
        try {

            $order = $this->orderRepository->createOrder($products);
            return $this->returnData(new OrderResource($order), __('Order has been created successfully'));

        } catch (\Exception $exception) {

            Log::error($exception->getMessage());
            return $this->errorMessage(__($exception->getMessage()), 500);

        }
    }

    public function updatePaymentStatus(int $orderId, string $status)
    {
        // Update the payment status through the repository
        $order = $this->orderRepository->updatePaymentStatus($orderId, $status);

        // If the update failed (due to the status check)
        if (!$order) {
            return $this->errorMessage('You cannot change the status of the order.', 400);
        }

        // Return the updated order data in the response
        return $this->returnData(new OrderResource($order), __('Order status has been updated successfully'));
    }
}
