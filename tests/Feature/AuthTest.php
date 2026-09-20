<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('login page is accessible', function () {
    $this->get('/login')->assertStatus(200);
});

test('user can login with valid credentials and is redirected to dashboard', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});

test('login with invalid credentials returns validation error', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('login with non-existent email returns validation error', function () {
    $response = $this->post('/login', [
        'email' => 'nobody@example.com',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('register page is accessible', function () {
    $this->get('/register')->assertStatus(200);
});

test('user can register and is redirected to dashboard', function () {
    $response = $this->post('/register', [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'Password1!',
        'password_confirmation' => 'Password1!',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
});

test('unauthenticated user is redirected to login when accessing protected route', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated user can access dashboard', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $this->actingAs($user)->get('/dashboard')->assertStatus(200);
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/logout');

    $this->assertGuest();
});
