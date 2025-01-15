<?php

namespace App\Repositories\User;

use App\Enum\OrderStatus;
use App\Enum\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderRepository
{
    public function __construct(protected Order $orderModel,protected ProductRepository $productRepository)
    {
    }

    /**
     * Create a new order.
     *
     * This method creates a new order, calculates the total amount based on the provided products,
     * adds a tax percentage (10%), and saves the final order details to the database.
     * If an error occurs, the transaction is rolled back, and an exception is thrown.
     *
     * @param array $products An array of products included in the order with their details (e.g., quantity, price).
     * @return Order|null Returns the created Order object on success, or null on failure.
     * @throws \Exception If an error occurs during the creation process.
     */
    public function createOrder($products)
    {
        try {
            // Begin a database transaction
            DB::beginTransaction();

            // Create a new order record
            $order = $this->createNewOrder();

            // Calculate the total amount for the order based on products
            $totalAmount = $this->calculateTotalAmount($products, $order);

            // Add tax (10%) to the total amount
            $totalAmount += $this->calculateTax($totalAmount);

            // Update the order's total amount and save the changes
            $order->update(['total_amount'=>$totalAmount]);

            // Refresh the order instance to include the latest changes
            $order->refresh();

            // Commit the transaction
            DB::commit();

            return $order;
        } catch (\Exception $exception) {
            // Rollback the transaction on error
            DB::rollBack();
            // Throw a new exception
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * Create a new order with initial total amount.
     *
     * @return Order
     */
    protected function createNewOrder()
    {
        return $this->orderModel->create([
            'total_amount' => 0,
            'user_id' => auth()->id()
        ]);
    }

    /**
     * Calculate the total amount of the order based on the products.
     *
     * @param array $products
     * @param Order $order
     * @return float
     */
    protected function calculateTotalAmount($products, $order)
    {
        $totalAmount = 0;

        foreach ($products as $product) {
            $productData = $this->productRepository->findById($product['id']);

            $order->products()->attach($productData->id, [
                'quantity' => $product['quantity'],
                'price' => $productData->price,
            ]);

            $totalAmount += $productData->price * $product['quantity'];
        }

        return $totalAmount;
    }

    /**
     * Calculate tax based on the total amount.
     *
     * @param float $totalAmount
     * @return float
     */
    protected function calculateTax($totalAmount)
    {
        return $totalAmount * 0.10; // 10% tax
    }

    /**
     * Retrieve an order by id.
     *
     * @param int $orderId
     * @return Order|null
     */
    public function getOrderById(int $orderId)
    {
        return $this->orderModel->find($orderId);
    }


    /**
     * Update the payment status of an order.
     *
     * @param int $orderId
     * @param string $status
     * @return Order|null|bool
     */
    public function updatePaymentStatus(Order $order, string $status)
    {
        // Proceed with the status update
        $order->update([
            'payment_status' => $status,
            'status' => PaymentStatus::isSuccessful($status) ? OrderStatus::getCompleted() : OrderStatus::getPending()
        ]);
        $order->refresh(); // Refresh the order instance to include the latest changes
        return $order;
    }


    /**
     * Check if the status change is not allowed.
     *
     * @param string $currentStatus
     * @param string $newStatus
     * @return bool
     */
    public function isPaymentStatusChangeAllowed(string $currentStatus, string $newStatus): bool
    {
        // Prevent updates if the current status is non-pending and the status is being changed
        if (in_array($currentStatus, PaymentStatus::getKeyListExcept(PaymentStatus::getPending()))) {
            return false;
        }

        // Prevent 'successful' orders from being changed to 'failed'
        if ($currentStatus === PaymentStatus::SUCCESSFUL && $newStatus === PaymentStatus::FAILED) {
            return false;
        }

        return true;
    }

}
