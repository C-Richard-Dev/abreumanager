<?php

namespace App\Actions\ResetPassword;

use App\Mail\PasswordResetEmail;
use App\Models\User;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SendEmailToReset
{
    public function execute(string $recipient): ?SentMessage
    {
        $userEmailExists = User::where('email', $recipient)->exists();

        if (! $userEmailExists) {
            throw ValidationException::withMessages([
                'email' => 'E-mail não encontrado.',
            ]);
        }

        $cacheKey = 'password-reset-email:'.hash('sha256', Str::lower(trim($recipient)));

        if (! Cache::add($cacheKey, true, 60)) {
            throw ValidationException::withMessages([
                'email' => 'Aguarde 1 minuto antes de solicitar outro e-mail de redefinição de senha.',
            ]);
        }

        try {
            $token = Str::random(60);

            DB::table('password_reset_tokens')
                ->updateOrInsert(
                    ['email' => $recipient],
                    ['token' => $token, 'created_at' => now()]
                );

            $linkToReset = route('password.reset.form', ['token' => $token]);

            $sentMessage = Mail::to($recipient)->send(new PasswordResetEmail($linkToReset));
        } catch (\Throwable $exception) {
            Cache::forget($cacheKey);

            throw $exception;
        }

        if ($sentMessage === null) {
            Cache::forget($cacheKey);

            return null;
        }

        Cache::put($cacheKey, true, 60);

        return $sentMessage;
    }
}
