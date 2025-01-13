<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    /**
     * Retrieve a user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Verify user credentials.
     *
     * @param User $user
     * @param string $password
     * @return bool
     */
    public function verifyCredentials(User $user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }
}
