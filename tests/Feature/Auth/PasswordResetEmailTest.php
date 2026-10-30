<?php

use App\Models\User;

test('forgot password form sends a reset email through the custom action', function () {
    $user = User::factory()->create();

    $response = $this->from(route('password.request'))
        ->post(route('password.send-reset-link'), ['email' => $user->email]);

    $response
        ->assertRedirect(route('password.request'))
        ->assertSessionHas('status', 'Password reset email sent.');

    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email,
    ]);
});

test('forgot password form shows an error when the email is not registered', function () {
    $response = $this->from(route('password.request'))
        ->post(route('password.send-reset-link'), ['email' => 'missing@example.com']);

    $response
        ->assertRedirect(route('password.request'))
        ->assertSessionHasErrors('email');
});
