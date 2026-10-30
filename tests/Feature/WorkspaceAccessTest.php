<?php

use App\Http\Controllers\Workspaces\ListUsersOfWorkspace;
use App\Models\Category;
use App\Models\Link;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;

it('does not include user ids in the workspace users JSON response', function () {
    $user = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Personal']);
    $workspace->users()->attach($user);

    $response = (new ListUsersOfWorkspace)(Request::create('/'), $workspace->uuid);

    $userJson = $response->getData(true)[0];

    expect($userJson)->not->toHaveKey('id');
    expect($userJson)->toMatchArray([
        'uuid' => $user->uuid,
        'name' => $user->name,
    ]);
});

it('includes workspace creators in the workspace listing', function () {
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
            ->where('workspaces.0.creator.name', $user->name));
});

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

it('lets the workspace creator remove a member', function () {
    $creator = User::factory()->create();
    $member = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Team', 'created_by' => $creator->id]);
    $workspace->users()->attach([$creator->id, $member->id]);

    $this->actingAs($creator)
        ->delete(route('workspaces.users.destroy', ['workspaceUuid' => $workspace->uuid, 'userUuid' => $member->uuid]))
        ->assertRedirect();

    expect($workspace->users()->whereKey($member->id)->exists())->toBeFalse();
    expect($workspace->users()->whereKey($creator->id)->exists())->toBeTrue();
});

it('forbids a non-creator member from removing a member', function () {
    $creator = User::factory()->create();
    $member = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Team', 'created_by' => $creator->id]);
    $workspace->users()->attach([$creator->id, $member->id]);

    $this->actingAs($member)
        ->delete(route('workspaces.users.destroy', ['workspaceUuid' => $workspace->uuid, 'userUuid' => $creator->uuid]))
        ->assertForbidden();

    expect($workspace->users()->whereKey($creator->id)->exists())->toBeTrue();
});

it('does not allow removing the workspace creator', function () {
    $creator = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Team', 'created_by' => $creator->id]);
    $workspace->users()->attach($creator);

    $this->actingAs($creator)
        ->delete(route('workspaces.users.destroy', ['workspaceUuid' => $workspace->uuid, 'userUuid' => $creator->uuid]))
        ->assertSessionHasErrors(['member' => 'O criador do workspace não pode ser removido.']);

    expect($workspace->users()->whereKey($creator->id)->exists())->toBeTrue();
});

it('returns 404 when a non-member tries to remove a member', function () {
    $creator = User::factory()->create();
    $member = User::factory()->create();
    $outsider = User::factory()->create();
    $workspace = Workspace::create(['name' => 'Team', 'created_by' => $creator->id]);
    $workspace->users()->attach([$creator->id, $member->id]);

    $this->actingAs($outsider)
        ->delete(route('workspaces.users.destroy', ['workspaceUuid' => $workspace->uuid, 'userUuid' => $member->uuid]))
        ->assertNotFound();

    expect($workspace->users()->whereKey($member->id)->exists())->toBeTrue();
});
