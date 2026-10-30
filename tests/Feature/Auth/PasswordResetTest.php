<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::resetPasswords());
});

test('reset password link screen can be rendered', function () {
    $response = $this->get(route('password.request'));

    $response->assertOk();
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $response = $this->get(route('password.reset', $notification->token));

        $response->assertOk();

        return true;
    });
});

test('password can be reset with valid token', function () {
    $user = User::factory()->create();
    $token = Str::random(60);

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => $token,
        'created_at' => now(),
    ]);

    $response = $this->post(route('password.update'), [
        'token' => $token,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('login'));

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
    $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
});

test('custom reset password form renders its token', function () {
    $user = User::factory()->create();
    $token = Str::random(60);

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => $token,
        'created_at' => now(),
    ]);

    $this->get(route('password.reset.form', ['token' => $token]))
        ->assertInertia(fn ($page) => $page
            ->component('reset-password/Form')
            ->where('token', $token));
});

test('password cannot be reset with invalid token', function () {
    $user = User::factory()->create();

    $response = $this->post(route('password.update'), [
        'token' => 'invalid-token',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors('token');
});
