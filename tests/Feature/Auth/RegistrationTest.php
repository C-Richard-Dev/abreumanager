<?php

use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    Mail::fake();

    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    $response->assertRedirect(route('workspaces.index', absolute: false));
    Mail::assertSent(WelcomeEmail::class, fn (WelcomeEmail $mail): bool => $mail->hasTo('test@example.com'));
});

test('invalid registrations do not send a welcome email', function () {
    Mail::fake();

    $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertSessionHasErrors('email');

    $this->assertDatabaseCount('users', 0);
    Mail::assertNothingSent();
});
