<?php

namespace Tests\Feature;

use App\Enum\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper method to authenticate a user.
     */
    private function authenticateUser(): User
    {
        // Create and authenticate the test user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user);
        return $user;
    }

    /**
     * Test the store route to create an order.
     *
     * @return void
     */
    public function test_create_order()
    {
        $this->authenticateUser(); // Authenticate user

        // Create test products to add to the order
        $product = Product::factory()->create();

        // Prepare the request data
        $data = [
            'products' => [
                [
                    'id' => $product->id,
                    'quantity' => 2,
                ]
            ]
        ];

        // Make a POST request to create the order
        $response = $this->postJson('/api/v1/user/orders', $data);

        // Assert the response is successful and structure is correct
        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'total_amount',
                    'status',
                    'payment_status',
                    'created_at',
                    'products' => [
                        '*' => [
                            'id',
                            'name',
                            'price',
                            'quantity',
                        ]
                    ]
                ],
                'message',
                'status',
                'code',
            ])
            ->assertJsonFragment([
                'message' => 'Order has been created successfully',
                'status' => true,
                'code' => 201,
            ]);
    }

    /**
     * Test the update payment status route for an order.
     *
     * @return void
     */
    public function test_update_payment_status()
    {
        $this->authenticateUser(); // Authenticate user

        // Create a test order
        $order = Order::factory()->create([
            'payment_status' => PaymentStatus::getPending(), // Assuming payment status starts as pending
        ]);

        // Prepare the request data to update payment status
        $data = [
            'order_id' => $order->id,
            'status' => PaymentStatus::getSuccessful(), // The new status
        ];

        // Make a PATCH request to update the payment status
        $response = $this->patchJson('/api/v1/user/orders/payment-status', $data);

        // Assert the response is successful and structure is correct
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'total_amount',
                    'status',
                    'payment_status',
                    'created_at',
                    'products' => [
                        '*' => [
                            'id',
                            'name',
                            'price',
                            'quantity',
                        ]
                    ]
                ],
                'message',
                'status',
                'code',
            ])
            ->assertJsonFragment([
                'message' => 'Order status has been updated successfully',
                'status' => true,
                'code' => 200,
            ]);
    }
    /**
     * Test the update payment status route with an invalid status change.
     *
     * @return void
     */
    public function test_update_payment_status_invalid_change()
    {
        $this->authenticateUser(); // Authenticate user

        // Create a test order with a payment status of 'successful'
        $order = Order::factory()->create([
            'payment_status' => 'successful',
        ]);

        // Prepare the request data to attempt an invalid payment status change
        $data = [
            'order_id' => $order->id,
            'status' => 'failed', // Trying to change 'successful' to 'failed'
        ];

        // Make a PATCH request to update the payment status
        $response = $this->patchJson('/api/v1/user/orders/payment-status', $data);

        // Assert the response returns a 400 status with an error message
        $response->assertStatus(400)
            ->assertJsonFragment([
                'message' => 'Order status cannot be changed.',
                'status' => false,
                'code' => 400,
                'data' => [],
            ]);
    }
}
