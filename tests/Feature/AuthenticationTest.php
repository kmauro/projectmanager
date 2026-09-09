<?php

use App\Domains\Users\Models\Role;
use App\Domains\Users\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    Role::query()->create(['role_name' => 'Administrador']);
    Role::query()->create(['role_name' => 'Usuario']);
});

it('renders the login and registration pages with Inertia', function () {
    $this->get(route('login'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Login')
            ->where('title', 'Iniciar sesión')
        );

    $this->get(route('register'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Signup')
            ->where('title', 'Registrarse')
        );
});

it('registers new accounts with the standard user role', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Ana',
        'surname' => 'Pérez',
        'username' => 'ana.perez',
        'phone' => null,
        'email' => 'ana@example.com',
        'password' => 'secret-password',
        'password_confirmation' => 'secret-password',
        'role_id' => 1,
    ]);

    $response->assertRedirectToRoute('home');
    $this->assertAuthenticated();

    $user = User::query()->where('username', 'ana.perez')->firstOrFail();

    expect($user->role_id)->toBe(2);
});

it('authenticates and logs out an existing user', function () {
    $user = User::factory()->create([
        'username' => 'maria',
        'password' => 'secret-password',
    ]);

    $this->post(route('login.store'), [
        'username' => 'maria',
        'password' => 'secret-password',
        'remember' => true,
    ])->assertRedirectToRoute('home');

    $this->assertAuthenticatedAs($user);

    $this->post(route('logout'))
        ->assertRedirectToRoute('login');

    $this->assertGuest();
});

it('rejects invalid credentials', function () {
    User::factory()->create([
        'username' => 'sofia',
        'password' => 'secret-password',
    ]);

    $this->from(route('login'))
        ->post(route('login.store'), [
            'username' => 'sofia',
            'password' => 'incorrect-password',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('username');

    $this->assertGuest();
});

it('updates only the authenticated profile and ignores role escalation fields', function () {
    $currentUser = User::factory()->create([
        'name' => 'Nombre original',
        'email' => 'current@example.com',
    ]);
    $otherUser = User::factory()->create([
        'email' => 'other@example.com',
    ]);

    $this->actingAs($currentUser)
        ->post(route('profile.update'), [
            'id' => $otherUser->id,
            'name' => 'Nombre actualizado',
            'surname' => $currentUser->surname,
            'email' => 'updated@example.com',
            'phone' => null,
            'role_id' => 1,
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($currentUser->refresh())
        ->name->toBe('Nombre actualizado')
        ->email->toBe('updated@example.com')
        ->role_id->toBe(2)
        ->and($otherUser->refresh()->email)->toBe('other@example.com');
});

it('forbids the user directory to non administrators', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('users.index'))
        ->assertForbidden();
});
