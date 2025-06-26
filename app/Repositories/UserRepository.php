<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function updateOrCreateFromSso(array $ssoUser): User
    {
        return User::updateOrCreate(
            ['email' => $ssoUser['email']],
            [
                'name' => $ssoUser['name'],
            ]
        );
    }
}