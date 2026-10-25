<?php

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Facades\Mail;

it('sends an invite email and stores the invite token for a workspace member', function () {
    Mail::fake();
    $sender = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Personal', 'created_by' => $sender->id]);
    $sender->workspaces()->attach($workspace);

    $this->actingAs($sender)
        ->post(route('workspaces.invite', ['workspaceUuid' => $workspace->uuid]), ['email' => 'Guest@Example.com'])
        ->assertRedirect();

    $this->assertDatabaseHas('invite_tokens', [
        'email' => 'guest@example.com',
        'workspace_id' => $workspace->id,
        'sender_id' => $sender->id,
    ]);
});

it('rejects inviting someone who is already a member', function () {
    $sender = User::factory()->create();
    $member = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Personal', 'created_by' => $sender->id]);
    $workspace->users()->attach([$sender->id, $member->id]);

    $this->actingAs($sender)
        ->post(route('workspaces.invite', ['workspaceUuid' => $workspace->uuid]), ['email' => $member->email])
        ->assertSessionHasErrors('email');

    $this->assertDatabaseCount('invite_tokens', 0);
});

it('returns 404 when a non-member tries to send an invite', function () {
    $outsider = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Private', 'created_by' => $outsider->id]);

    $this->actingAs($outsider)
        ->post(route('workspaces.invite', ['workspaceUuid' => $workspace->uuid]), ['email' => 'guest@example.com'])
        ->assertNotFound();

    $this->assertDatabaseCount('invite_tokens', 0);
});

it('rejects an invalid invite email', function () {
    $sender = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Personal', 'created_by' => $sender->id]);
    $sender->workspaces()->attach($workspace);

    $this->actingAs($sender)
        ->post(route('workspaces.invite', ['workspaceUuid' => $workspace->uuid]), ['email' => 'not-an-email'])
        ->assertSessionHasErrors(['email' => 'Informe um e-mail válido.']);

    $this->assertDatabaseCount('invite_tokens', 0);
});
