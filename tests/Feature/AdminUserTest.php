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

// --- Index ---

test('unauthenticated user is redirected from admin users index', function () {
    $this->get('/admin/users')->assertRedirect('/login');
});

test('user role cannot access admin users index', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $this->actingAs($user)->get('/admin/users')->assertStatus(403);
});

test('admin can access users index', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $this->actingAs($admin)->get('/admin/users')->assertStatus(200);
});

test('super-admin can access users index', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $this->actingAs($superAdmin)->get('/admin/users')->assertStatus(200);
});

// --- Edit ---

test('super-admin can access user edit page', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $target = User::factory()->create();

    $this->actingAs($superAdmin)->get("/admin/users/{$target->id}/edit")->assertStatus(200);
});

// --- Update ---

test('super-admin can update user name and email', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $target = User::factory()->create();

    $this->actingAs($superAdmin)->patch("/admin/users/{$target->id}", [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ])->assertRedirect('/admin/users');

    $this->assertDatabaseHas('users', [
        'id' => $target->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);
});

test('user update syncs roles correctly', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $target = User::factory()->create();
    $target->assignRole('user');

    $this->actingAs($superAdmin)->patch("/admin/users/{$target->id}", [
        'name' => $target->name,
        'email' => $target->email,
        'roles' => ['admin'],
    ]);

    $target->refresh();
    expect($target->hasRole('admin'))->toBeTrue();
    expect($target->hasRole('user'))->toBeFalse();
});

test('user update with no roles removes all roles', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $target = User::factory()->create();
    $target->assignRole('user');

    $this->actingAs($superAdmin)->patch("/admin/users/{$target->id}", [
        'name' => $target->name,
        'email' => $target->email,
    ]);

    $target->refresh();
    expect($target->roles)->toHaveCount(0);
});

// --- Destroy ---

test('super-admin can delete another user', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $target = User::factory()->create();

    $this->actingAs($superAdmin)->delete("/admin/users/{$target->id}")
        ->assertRedirect('/admin/users');

    $this->assertSoftDeleted('users', ['id' => $target->id]);
});

test('user cannot delete their own account', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $this->actingAs($superAdmin)->delete("/admin/users/{$superAdmin->id}")
        ->assertStatus(403);
});

// --- Admin role access to roles section ---

test('admin cannot access admin roles index', function () {
    $admin = User::factory()->create(['email_verified_at' => now()]);
    $admin->assignRole('admin');

    $this->actingAs($admin)->get('/admin/roles')->assertStatus(403);
});
