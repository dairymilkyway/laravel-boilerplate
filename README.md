# Laravel Boilerplate

A production-ready Laravel starter kit with authentication, role-based access control, and an admin panel scaffold. Clone it, configure it, and start building your feature - not your plumbing.

## What's included

| Layer | Package | Version |
|---|---|---|
| Framework | Laravel | 13.x |
| Auth scaffolding | Laravel Breeze (Blade stack) | 2.x |
| RBAC | Spatie Laravel Permission | 8.x |
| Testing | Pest + Pest Laravel Plugin | 4.x |
| Code style | Laravel Pint | 1.x |
| Static analysis | Larastan (PHPStan for Laravel) | 3.x |
| Frontend | Tailwind CSS + Vite | 3.x / 8.x |

**Auth (Breeze)** - Login, registration, password reset, email verification. All Blade views, no JavaScript framework.

**RBAC (Spatie)** - Database-driven roles and permissions. Three roles pre-seeded: `super-admin`, `admin`, `user`. Blade directives (`@role`, `@can`, `@hasanyrole`) work out of the box.

**Admin panel** - Protected route group at `/admin`. Sidebar layout with Dashboard, Users, and Roles sections. User management CRUD with role assignment. Role management with permission sync.

---

## Requirements

- PHP 8.2 or higher (tested on 8.5)
- Composer 2.x
- Node.js 18+ and npm
- MySQL, PostgreSQL, or SQLite

---

## Installation

**1. Clone the repository**

```bash
git clone https://github.com/your-username/laravel-boilerplate.git
cd laravel-boilerplate
```

**2. Install PHP dependencies**

```bash
composer install
```

**3. Install Node dependencies and build assets**

```bash
npm install
npm run build
```

**4. Configure your environment**

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database connection:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_boilerplate
DB_USERNAME=root
DB_PASSWORD=
```

For SQLite (quickest local setup):

```env
DB_CONNECTION=sqlite
```

Then create the database file:

```bash
touch database/database.sqlite
```

**5. Run migrations and seed default roles/users**

```bash
php artisan migrate --seed
```

**6. Start the development server**

```bash
php artisan serve
```

Visit `http://localhost:8000`. Log in with the default credentials below.

---

## Default credentials

| Role | Email | Password |
|---|---|---|
| super-admin | superadmin@example.com | password |
| admin | admin@example.com | password |

Change these immediately before deploying to any environment.

---

## Roles and permissions

Three roles are created by `RoleSeeder`:

| Role | Permissions |
|---|---|
| `super-admin` | Bypasses all permission checks (Gate::before) |
| `admin` | view users, manage users, view roles |
| `user` | None by default |

Four permissions are seeded: `view users`, `manage users`, `view roles`, `manage roles`.

### Assigning a role to a user

```php
$user->assignRole('admin');
$user->syncRoles(['admin', 'user']);
```

### Protecting routes

```php
// Require a specific role
Route::middleware(['auth', 'role:admin'])->group(function () {
    // ...
});

// Require any of multiple roles
Route::middleware(['auth', 'role:super-admin|admin'])->group(function () {
    // ...
});

// Require a specific permission
Route::middleware(['auth', 'permission:manage users'])->group(function () {
    // ...
});
```

### Blade directives

```blade
@role('admin')
    <p>Only admins see this.</p>
@endrole

@hasanyrole('super-admin|admin')
    <a href="/admin">Admin Panel</a>
@endhasanyrole

@can('manage users')
    <button>Delete User</button>
@endcan
```

---

## Admin panel

The admin panel lives at `/admin` and is protected by `auth + verified + role:super-admin|admin` middleware.

| Route | Description |
|---|---|
| GET /admin | Dashboard with user and role counts |
| GET /admin/users | Paginated user list with role badges |
| GET /admin/users/{user}/edit | Edit name, email, and role assignments |
| PATCH /admin/users/{user} | Apply user updates |
| DELETE /admin/users/{user} | Delete a user (cannot delete yourself) |
| GET /admin/roles | Role list with permission counts |
| GET /admin/roles/{role}/edit | Edit role name and sync permissions |
| PATCH /admin/roles/{role} | Apply role updates |

Note: `/admin/roles/*` routes additionally require the `manage roles` permission. The `admin` role has `view roles` but not `manage roles`, so it can see the list but cannot edit roles.

---

## Running tests

```bash
php artisan test
```

Or directly via Pest:

```bash
vendor/bin/pest
```

Run a specific test file:

```bash
vendor/bin/pest tests/Feature/AdminUserTest.php
```

---

## Code quality

**Fix code style (Pint):**

```bash
vendor/bin/pint
```

**Static analysis (PHPStan level 5):**

```bash
vendor/bin/phpstan analyse
```

---

## What is NOT included

This boilerplate intentionally excludes the following. Add them when your project needs them:

- **API / Sanctum** - No API routes, no token authentication. Add `laravel/sanctum` if you need a REST API or mobile app backend.
- **Activity logging** - No audit trail. Add `spatie/laravel-activitylog` if you need to track who changed what.
- **Media uploads** - No file management. Add `spatie/laravel-medialibrary` or use Flysystem directly.
- **Multi-tenancy** - All data is single-tenant. Add `stancl/tenancy` for multi-tenant SaaS.
- **Email queues** - Mail is synchronous by default. Configure a queue driver (`QUEUE_CONNECTION=redis`) and use `ShouldQueue` on mailables in production.
- **Two-factor authentication** - Not included. Add `laravel/fortify` or `pragmarx/google2fa-laravel` if needed.
- **Livewire / Alpine.js** - The admin panel uses plain Blade and vanilla JS only. Add these if you want reactive components without a full SPA.

---

## Deployment

For production, additionally run:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

For one-click deployment to a managed Laravel platform, see [Laravel Cloud](https://cloud.laravel.com/).

---

## License

MIT. Use it freely.
