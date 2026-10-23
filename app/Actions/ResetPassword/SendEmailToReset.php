<?php

namespace App\Actions\ResetPassword;

use Illuminate\Mail\Message;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendEmailToReset
{
    public function execute(string $recipient): ?SentMessage
    {

        $token = Str::random(60);

        DB::table('password_reset_tokens')
            ->updateOrInsert(
                ['email' => $recipient],
                ['token' => $token, 'created_at' => now()]
            );

        $linkToReset = route('password.reset.form', ['token' => $token]);

        return Mail::raw(
            'Este é um e-mail solicitado para redefinição de senha. Clique no link a seguir para redefinir sua senha: '.$linkToReset,
            function (Message $message) use ($recipient): void {
                $message
                    ->to($recipient)
                    ->subject('Redefinição de senha');
            },
        );
    }
}
