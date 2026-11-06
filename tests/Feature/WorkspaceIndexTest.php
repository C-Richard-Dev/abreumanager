<?php

use App\Models\User;
use App\Models\Workspace;

test('guests are redirected to login from the workspace list', function () {
    $this->get(route('workspaces.index'))
        ->assertRedirect(route('login'));
});

test('authenticated users can view their workspaces', function () {
    $user = User::factory()->create();
    $workspace = Workspace::create([
        'name' => 'Personal',
        'created_by' => $user->id,
    ]);
    $user->workspaces()->attach($workspace);

    $this->actingAs($user)
        ->get(route('workspaces.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Workspace/Index')
            ->has('workspaces', 1)
            ->where('workspaces.0.name', 'Personal'));
});

test('authenticated users without a workspace are sent to create one', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('workspaces.index'))
        ->assertRedirect(route('workspaces.create'));
});
