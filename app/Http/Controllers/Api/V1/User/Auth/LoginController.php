<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use App\DTO\LoginDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Services\User\AuthService;

class LoginController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $credentials = new LoginDTO(email: $request->email,password: $request->password);
        return $this->authService->login($credentials);
    }
}
