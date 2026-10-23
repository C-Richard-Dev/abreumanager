<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordOfUser
{
    public function execute(string $email, string $password): void
    {
        $user = User::where('email', $email)->firstOrFail();
        $user->password = Hash::make($password);
        $user->save();
    }
}