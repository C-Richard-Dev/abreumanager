<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ResetPasswordOfUser
{
    public function execute(string $password, string $token): void
    {
        DB::transaction(function () use ($password, $token): void {
            $resetRecord = DB::table('password_reset_tokens')
                ->where('token', $token)
                ->where('created_at', '>=', now()->subMinutes((int) config('auth.passwords.users.expire', 60)))
                ->lockForUpdate()
                ->first();

            if (! $resetRecord) {
                throw ValidationException::withMessages([
                    'token' => 'O link de redefinição é inválido ou expirou.',
                ]);
            }

            $tokenWasConsumed = DB::table('password_reset_tokens')
                ->where('email', $resetRecord->email)
                ->where('token', $token)
                ->delete();

            if ($tokenWasConsumed !== 1) {
                throw ValidationException::withMessages([
                    'token' => 'O link de redefinição é inválido ou expirou.',
                ]);
            }

            $user = User::where('email', $resetRecord->email)->first();

            if (! $user) {
                throw ValidationException::withMessages([
                    'token' => 'O link de redefinição é inválido ou expirou.',
                ]);
            }

            $user->password = Hash::make($password);
            $user->save();
        });
    }
}
