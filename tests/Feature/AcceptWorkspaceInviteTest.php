<?php

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Facades\DB;

function createInvite(User $sender, Workspace $workspace, string $email, ?string $createdAt = null): string
{
    $token = str_repeat('a', 60);

    DB::table('invite_tokens')->insert([
        'email' => $email,
        'token' => $token,
        'workspace_id' => $workspace->id,
        'sender_id' => $sender->id,
        'created_at' => $createdAt ?? now(),
        'updated_at' => now(),
    ]);

    return $token;
}

it('renders the invite page for the invited user', function () {
    $sender = User::factory()->create();
    $guest = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Team', 'created_by' => $sender->id]);
    $token = createInvite($sender, $workspace, $guest->email);

    $this->actingAs($guest)
        ->get(route('invite.show', ['token' => $token]))
        ->assertInertia(fn ($page) => $page
            ->component('invite/Accept')
            ->where('workspaceName', 'Team')
            ->where('senderName', $sender->name));
});

it('adds the invited user to the workspace and consumes the invite', function () {
    $sender = User::factory()->create();
    $guest = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Team', 'created_by' => $sender->id]);
    $token = createInvite($sender, $workspace, $guest->email);

    $this->actingAs($guest)
        ->post(route('invite.accept', ['token' => $token]))
        ->assertRedirect(route('invite.success'));

    expect($workspace->users()->whereKey($guest->id)->exists())->toBeTrue();
    $this->assertDatabaseCount('invite_tokens', 0);
});

it('returns 404 when the invite belongs to another email', function () {
    $sender = User::factory()->create();
    $guest = User::factory()->create();
    $other = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Team', 'created_by' => $sender->id]);
    $token = createInvite($sender, $workspace, $guest->email);

    $this->actingAs($other)
        ->get(route('invite.show', ['token' => $token]))
        ->assertNotFound();
});

it('rejects accepting an invite that belongs to another email', function () {
    $sender = User::factory()->create();
    $guest = User::factory()->create();
    $other = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Team', 'created_by' => $sender->id]);
    $token = createInvite($sender, $workspace, $guest->email);

    $this->actingAs($other)
        ->post(route('invite.accept', ['token' => $token]))
        ->assertSessionHasErrors(['token' => 'O convite é inválido ou expirou.']);

    expect($workspace->users()->whereKey($other->id)->exists())->toBeFalse();
    $this->assertDatabaseCount('invite_tokens', 1);
});

it('rejects an expired invite', function () {
    $sender = User::factory()->create();
    $guest = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Team', 'created_by' => $sender->id]);
    $token = createInvite($sender, $workspace, $guest->email, now()->subDays(8)->toDateTimeString());

    $this->actingAs($guest)
        ->post(route('invite.accept', ['token' => $token]))
        ->assertSessionHasErrors('token');

    expect($workspace->users()->whereKey($guest->id)->exists())->toBeFalse();
});

it('redirects guests to the login page', function () {
    $this->get(route('invite.show', ['token' => str_repeat('a', 60)]))
        ->assertRedirect(route('login'));
});

it('renders the invite success page with a login link', function () {
    $this->get(route('invite.success'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('invite/Success'));
});
