<?php

use App\Actions\ResetPassword\SendEmailToReset;
use App\Mail\PasswordResetEmail;
use App\Models\User;
use Illuminate\Mail\PendingMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

test('password reset email is blocked for the same recipient for one minute', function () {
    $user = User::factory()->create();
    $action = app(SendEmailToReset::class);

    $action->execute($user->email);

    $this->travel(59)->seconds();

    expect(fn () => $action->execute($user->email))
        ->toThrow(ValidationException::class);

    $this->travel(1)->second();

    $action->execute($user->email);
});

test('password reset email cooldown is scoped to the recipient', function () {
    $firstUser = User::factory()->create();
    $secondUser = User::factory()->create();
    $action = app(SendEmailToReset::class);

    $action->execute($firstUser->email);
    $action->execute($secondUser->email);

    expect(fn () => $action->execute($firstUser->email))
        ->toThrow(RuntimeException::class, 'Aguarde 1 minuto');
});

test('password reset email can be retried immediately after a delivery failure', function () {
    $user = User::factory()->create();
    $action = app(SendEmailToReset::class);
    $attempts = 0;
    $pendingMail = Mockery::mock(PendingMail::class);

    Mail::shouldReceive('to')
        ->twice()
        ->with($user->email)
        ->andReturn($pendingMail);

    $pendingMail->shouldReceive('send')
        ->twice()
        ->with(Mockery::type(PasswordResetEmail::class))
        ->andReturnUsing(function () use (&$attempts) {
            if ($attempts++ === 0) {
                throw new RuntimeException('Falha ao enviar e-mail.');
            }

            return null;
        });

    expect(fn () => $action->execute($user->email))
        ->toThrow(RuntimeException::class, 'Falha ao enviar e-mail.');

    $action->execute($user->email);
});
