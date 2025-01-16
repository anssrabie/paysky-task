<?php

namespace App\Services\User;

use App\Enum\PaymentStatus;
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
            return $this->returnData(new OrderResource($order), __('Order has been created successfully'),201);

        } catch (\Exception $exception) {

            Log::error($exception->getMessage());
            return $this->errorMessage(__($exception->getMessage()), 500);

        }
    }

    public function updatePaymentStatus(int $orderId, string $status)
    {
        // Retrieve the order from the repository
        $order = $this->orderRepository->getOrderById($orderId);

        // If the order doesn't exist, return a specific error
        if (!$order) {
            return $this->errorMessage('Order not found.', 404);
        }

        // Prevent updating from 'successful' to 'failed'
        $paymentStatus = $order->payment_status;
        if (!$this->orderRepository->isPaymentStatusChangeAllowed($paymentStatus,$status)) {
            return $this->errorMessage('Order status cannot be changed.', 400);
        }

        // Update the payment status
        $order = $this->orderRepository->updatePaymentStatus($order, $status);

        // Return the updated order data in the response
        return $this->returnData(new OrderResource($order), __('Order status has been updated successfully'));
    }



}
