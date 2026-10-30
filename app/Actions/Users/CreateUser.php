<?php

namespace App\Actions\Users;

use App\Models\User;

class CreateUser
{
    /**
     * @param  array{name: string, email: string, type: string, password: string}  $input
     */
    public function execute(array $input): User
    {
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'type' => $input['type'],
            'password' => $input['password'],
        ]);
    }
}
