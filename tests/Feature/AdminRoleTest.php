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

test('unauthenticated user is redirected from admin roles index', function () {
    $this->get('/admin/roles')->assertRedirect('/login');
});

test('user role cannot access admin roles index', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $this->actingAs($user)->get('/admin/roles')->assertStatus(403);
});

test('super-admin can access roles index', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $this->actingAs($superAdmin)->get('/admin/roles')->assertStatus(200);
});

// --- Edit ---

test('super-admin can access role edit page', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $role = Role::where('name', 'user')->first();

    $this->actingAs($superAdmin)->get("/admin/roles/{$role->id}/edit")->assertStatus(200);
});

// --- Update ---

test('super-admin can update role permissions', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $role = Role::where('name', 'user')->first();

    $this->actingAs($superAdmin)->patch("/admin/roles/{$role->id}", [
        'name' => 'user',
        'permissions' => ['view users'],
    ])->assertRedirect('/admin/roles');

    $role->refresh();
    expect($role->hasPermissionTo('view users'))->toBeTrue();
});

test('role update syncs permissions correctly', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $role = Role::where('name', 'user')->first();
    $role->givePermissionTo('view users');

    $this->actingAs($superAdmin)->patch("/admin/roles/{$role->id}", [
        'name' => 'user',
        'permissions' => ['view roles'],
    ]);

    $role->refresh();
    expect($role->hasPermissionTo('view roles'))->toBeTrue();
    expect($role->hasPermissionTo('view users'))->toBeFalse();
});

test('role update with no permissions removes all permissions', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $role = Role::where('name', 'user')->first();
    $role->givePermissionTo('view users');

    $this->actingAs($superAdmin)->patch("/admin/roles/{$role->id}", [
        'name' => 'user',
    ]);

    $role->refresh();
    expect($role->permissions)->toHaveCount(0);
});

test('roles index passes roles with permission counts to view', function () {
    $superAdmin = User::factory()->create(['email_verified_at' => now()]);
    $superAdmin->assignRole('super-admin');

    $response = $this->actingAs($superAdmin)->get('/admin/roles');

    $response->assertViewHas('roles');
});
