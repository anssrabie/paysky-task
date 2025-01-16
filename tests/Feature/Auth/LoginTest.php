<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test login with valid credentials.
     *
     * @return void
     */
    public function test_login_with_valid_credentials()
    {
        // Create a test user
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'), // hashed password
        ]);

        // Prepare the login request data
        $data = [
            'email' => 'test@example.com',
            'password' => 'password', // plain password
        ];

        // Make a POST request to the login endpoint
        $response = $this->postJson('/api/v1/auth/login', $data);

        // Assert that the response has a successful status
        $response->assertStatus(200);

        // Assert that the response contains a token
        $response->assertJsonStructure([
            'data' => [
                'token',
                'profile' => [
                    'id',
                    'name',
                    'email',
                ]
            ],
            'message',
            'status',
            'code',
        ]);
    }

    public function test_login_with_not_valid_credentials()
    {
        // Create a test user
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make(Str::random()),
        ]);

        // Prepare the login request data
        $data = [
            'email' => 'test@example.com',
            'password' => 'password', // plain password
        ];

        // Make a POST request to the login endpoint
        $response = $this->postJson('/api/v1/auth/login', $data);

        // Assert that the response has a 422 Unprocessable Entity status
        $response->assertStatus(422);

        // Assert that the response contains a token
        $response->assertJsonStructure([
            'data' => [],
            'message',
            'status',
            'code',
        ]);
    }
}
