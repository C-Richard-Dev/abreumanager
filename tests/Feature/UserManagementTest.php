<?php

use App\Enums\UserType;
use App\Models\User;
use App\Models\Workspace;

function adminUser(): User
{
    return User::factory()->create(['type' => UserType::ADMIN->value]);
}

test('guests are redirected to login', function () {
    $this->get(route('users.index'))->assertRedirect(route('login'));
});

test('regular users cannot access users routes', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('users.index'))->assertNotFound();
    $this->actingAs($user)->post(route('users.store'), [])->assertNotFound();
    $this->actingAs($user)->delete(route('users.destroy', $user->uuid))->assertNotFound();
});

test('admin can list users without ids', function () {
    $this->actingAs(adminUser())
        ->get(route('users.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('User/Index')
            ->has('users.0.uuid')
            ->missing('users.0.id'));
});

test('admin can create a user', function () {
    $this->actingAs(adminUser())->post(route('users.store'), [
        'name' => 'Novo',
        'email' => 'novo@example.com',
        'type' => 'regular',
        'password' => 'password-123',
        'password_confirmation' => 'password-123',
    ])->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', ['email' => 'novo@example.com', 'type' => 'regular']);
});

test('creating a user validates input', function () {
    $this->actingAs(adminUser())
        ->post(route('users.store'), ['type' => 'foo'])
        ->assertSessionHasErrors(['name', 'email', 'type', 'password']);
});

test('admin can update a user keeping the password when blank', function () {
    $target = User::factory()->create();
    $hash = $target->password;

    $this->actingAs(adminUser())->put(route('users.update', $target->uuid), [
        'name' => 'Editado',
        'email' => $target->email,
        'type' => 'admin',
        'password' => '',
    ])->assertRedirect(route('users.index'));

    $target->refresh();
    expect($target->name)->toBe('Editado')
        ->and($target->type)->toBe(UserType::ADMIN)
        ->and($target->password)->toBe($hash);
});

test('admin cannot change own type', function () {
    $admin = adminUser();

    $this->actingAs($admin)->put(route('users.update', $admin->uuid), [
        'name' => $admin->name,
        'email' => $admin->email,
        'type' => 'regular',
    ])->assertSessionHasErrors('type');

    expect($admin->refresh()->type)->toBe(UserType::ADMIN);
});

test('admin can delete a user', function () {
    $target = User::factory()->create();

    $this->actingAs(adminUser())
        ->delete(route('users.destroy', $target->uuid))
        ->assertRedirect(route('users.index'));

    $this->assertDatabaseMissing('users', ['id' => $target->id]);
});

test('admin cannot delete self', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->delete(route('users.destroy', $admin->uuid))
        ->assertSessionHasErrors('user');

    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('user who created workspaces cannot be deleted', function () {
    $target = User::factory()->create();
    Workspace::create(['name' => 'WS', 'created_by' => $target->id]);

    $this->actingAs(adminUser())
        ->delete(route('users.destroy', $target->uuid))
        ->assertSessionHasErrors('user');

    $this->assertDatabaseHas('users', ['id' => $target->id]);
});
