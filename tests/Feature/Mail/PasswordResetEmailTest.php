<?php

use App\Mail\PasswordResetEmail;

test('password reset email contains the reset link and instructions', function () {
    $resetEmail = new PasswordResetEmail('https://example.test/reset-password/example-token');

    $resetEmail->assertSeeInHtml('Redefinição de senha');
    $resetEmail->assertSeeInHtml('Redefinir senha');
    $resetEmail->assertSeeInHtml('https://example.test/reset-password/example-token');
    $resetEmail->assertSeeInText('nova senha');
});
