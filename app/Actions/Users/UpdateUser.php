<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Validation\ValidationException;

class UpdateUser
{
    /**
     * @param  array{name: string, email: string, type: string, password?: string|null}  $input
     */
    public function execute(User $user, User $authenticatedUser, array $input): User
    {
        if ($user->is($authenticatedUser) && $input['type'] !== $user->type->value) {
            throw ValidationException::withMessages([
                'type' => 'Você não pode alterar o seu próprio tipo de usuário.',
            ]);
        }

        $attributes = [
            'name' => $input['name'],
            'email' => $input['email'],
            'type' => $input['type'],
        ];

        if (! empty($input['password'])) {
            $attributes['password'] = $input['password'];
        }

        $user->update($attributes);

        return $user;
    }
}
