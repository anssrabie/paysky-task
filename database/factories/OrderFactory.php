<?php

namespace Database\Factories;

use App\Enum\OrderStatus;
use App\Enum\PaymentStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Create a user and assign the user_id
        $user = User::factory()->create();

        return [
            'user_id' => $user->id,  // Make sure user_id is set
            'total_amount' => $this->faker->numberBetween(100, 1000),
            'status' => $this->faker->randomElement(['pending', 'completed']),
            'payment_status' => $this->faker->randomElement(['pending', 'paid', 'failed']),
        ];
    }
}
