<?php

namespace App\Services\User;

use App\Http\Resources\User\ProfileResource;
use App\Repositories\User\AuthRepository;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;

class AuthService extends BaseService
{
    public function __construct(protected AuthRepository $authRepository)
    {
    }
    /**
     * Handle user login.
     *
     * @param array $credentials
     * @return array
     */
    public function login(array $credentials): JsonResponse
    {
        // Retrieve user by email
        $user = $this->authRepository->getUserByEmail($credentials['email']);

        // Verify credentials
        if (!$user || !$this->authRepository->verifyCredentials($user, $credentials['password'])) {
            return $this->errorMessage('Invalid email or password.');
        }

        // Generate authentication token
        $data['token'] = $user->createToken('auth_token')->plainTextToken;
        $data['profile'] = new ProfileResource($user);
        return $this->returnData($data,'Login successful.');
    }
}
