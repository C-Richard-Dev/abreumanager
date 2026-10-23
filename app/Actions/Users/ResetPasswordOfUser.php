<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResetPasswordOfUser
{
    public function execute(string $email, string $password, string $token): void
    {
        try {
            $validToken = DB::table('password_resets')
                ->where('email', $email)
                ->where('token', $token)
                ->first();
            
            if(!$validToken) {
                throw new \Exception('Token inválido ou expirado.');
            }

            $user = User::where('email', $email)->firstOrFail();
            $user->password = bcrypt($password);
            $user->save();

            $validToken->delete();

        } catch (\Exception $e) {
            throw $e;
        } 
    }
}