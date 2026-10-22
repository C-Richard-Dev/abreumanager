<?php

use App\Models\Category;
use App\Models\Link;
use App\Models\User;
use App\Models\Workspace;

it('allows a workspace member to view the workspace', function () {
    $user = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Personal']);
    $user->workspaces()->attach($workspace);

    $this->actingAs($user)
        ->get(route('workspaces.show', ['workspaceUuid' => $workspace->uuid]))
        ->assertOk();
});

it('hides a workspace from users who are not members', function () {
    $user = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Private']);

    $this->actingAs($user)
        ->get(route('workspaces.show', ['workspaceUuid' => $workspace->uuid]))
        ->assertNotFound();
});

it('prevents non-members from deleting a workspace category', function () {
    $user = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Private']);
    $category = Category::create([
        'name' => 'Private category',
        'workspace_id' => $workspace->id,
    ]);

    $this->actingAs($user)
        ->delete(route('categories.destroy', ['categoryUuid' => $category->uuid]))
        ->assertNotFound();

    $this->assertModelExists($category);
});

it('prevents non-members from deleting a workspace link', function () {
    $user = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Private']);
    $link = Link::create([
        'name' => 'Private link',
        'url' => 'https://example.com',
        'workspace_id' => $workspace->id,
    ]);

    $this->actingAs($user)
        ->delete(route('links.destroy', ['linkUuid' => $link->uuid]))
        ->assertNotFound();

    $this->assertModelExists($link);
});
