<?php

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
});

test('unauthenticated user is redirected from admin dashboard', function () {
    $response = $this->get('/admin');

    $response->assertRedirect('/login');
});

test('authenticated user without admin role cannot access admin dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $response = $this->actingAs($user)->get('/admin');

    $response->assertStatus(403);
});

test('admin role can access admin dashboard', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get('/admin');

    $response->assertStatus(200);
});

test('super-admin role can access admin dashboard', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole('super-admin');

    $response = $this->actingAs($user)->get('/admin');

    $response->assertStatus(200);
});

test('admin dashboard passes user and role counts to the view', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    User::factory()->count(3)->create();
    $roleCount = Role::count();

    $response = $this->actingAs($superAdmin)->get('/admin');

    $response->assertViewHas('totalUsers');
    $response->assertViewHas('totalRoles', $roleCount);
});

test('admin with unverified email is redirected from admin dashboard', function () {
    $admin = User::factory()->create(['email_verified_at' => null]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->get('/admin');

    $response->assertRedirect();
    expect($response->headers->get('Location'))->toContain('verify-email');
});
