<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductTest extends TestCase
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
     * Test the index route to retrieve all products.
     *
     * @return void
     */
    public function test_get_all_products()
    {
        $this->authenticateUser(); // Authenticate user

        // Create test products
        Product::factory()->count(5)->create();

        // Make a GET request to the products index route
        $response = $this->getJson('/api/v1/user/products');

        // Assert the response is successful and structure is correct
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'created_at',
                    ],
                ],
                'message',
                'status',
                'code',
            ])
            ->assertJsonCount(5, 'data'); // Assert that 5 products are returned
    }

    /**
     * Test the show route to retrieve a single product.
     *
     * @return void
     */
    public function test_get_single_product()
    {
        $this->authenticateUser(); // Authenticate user

        // Create a test product
        $product = Product::factory()->create();

        // Make a GET request to the product show route
        $response = $this->getJson("/api/v1/user/products/{$product->id}");

        // Assert the response is successful and structure is correct
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'price',
                    'created_at',
                ],
                'message',
                'status',
                'code',
            ])
            ->assertJsonFragment([
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'created_at' => $product->created_at->format('d-m-Y'),
            ]);
    }

    /**
     * Test the show route with a non-existing product.
     *
     * @return void
     */
    public function test_get_non_existing_product()
    {
        $this->authenticateUser(); // Authenticate user

        // Make a GET request to the product show route with a non-existing product ID
        $response = $this->getJson('/api/v1/user/products/99999');

        // Assert the response returns 404 with the appropriate message
        $response->assertStatus(404)
            ->assertJsonFragment([
                'message' => 'Product is not exists',
                'status' => false,
                'code' => 404,
                'data' => [],
            ]);
    }
}
