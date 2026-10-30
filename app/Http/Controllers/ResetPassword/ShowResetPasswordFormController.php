<?php

namespace App\Http\Controllers\ResetPassword;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ShowResetPasswordFormController
{
    public function __invoke(string $token): Response
    {
        $resetRecord = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->where('created_at', '>=', now()->subMinutes((int) config('auth.passwords.users.expire', 60)))
            ->first();

        abort_unless($resetRecord, 404);

        return Inertia::render('reset-password/Form', [
            'token' => $token,
        ]);
    }
}
