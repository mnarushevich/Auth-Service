# AGENTS.md

This file provides guidance to Codex (Codex.ai/code) when working with code in this repository.

## Agent Instructions

- **All `php` and `composer` commands MUST be prefixed with `herd`** (e.g., `herd php artisan ...`, `herd composer ...`). Never run `php` or `composer` directly.
- The test framework is **Pest**.
- **Git commit messages** must start with the branch ticket prefix. Extract the prefix from the branch name (e.g., branch `MN-40-upgrade-laravel-to-13` → prefix `MN-40`) and format commits as `MN-40: <commit text>`. Keep commit texts short and precise.

## Project Overview

Auth Service is a Laravel 13 microservice for user authentication and management. It provides JWT-based authentication, RBAC using Spatie Permission, and publishes user events to Kafka.

## Development Environment

The project uses Laravel Herd for local development. Docker Compose provides supporting services (MySQL, Redis, Kafka, Mailpit).

```bash
# Start supporting services
docker compose up -d

# The Laravel app runs via Herd at: https://auth-service.test
```

## Common Commands

### Testing
```bash
# Run all tests (type-coverage, rector, lint, types, unit)
herd composer test

# Run unit/feature tests only
herd composer test:unit
# or
herd php artisan test

# Run specific test file
herd php artisan test tests/Integration/Users/CreateUserEndpointTest.php

# Run tests matching a name
herd php artisan test --filter=testName
```

### Code Quality
```bash
# Fix code style with Pint
herd composer test:lint:fix
# or
herd vendor/bin/pint --dirty

# Static analysis with PHPStan (level 5)
herd composer test:types

# Rector for code upgrades
herd composer test:rector      # dry-run
herd composer test:rector:fix  # apply changes

# Type coverage (min 95%)
herd composer test:type-coverage
```

## Architecture

### API Structure
- API prefix: `/api/v1` (configured in `bootstrap/app.php`)
- Routes defined in `routes/api.php`
- Single-action controllers in `app/Http/Controllers/`
- Form Request validation classes in `app/Http/Requests/`
- Eloquent API Resources in `app/Http/Resources/`

### Authentication
- JWT authentication via `tymon/jwt-auth`
- Guard configured as `api` with JWT driver
- Auth endpoints: login, logout, refresh, verify, me, password reset

### Authorization
- Spatie Laravel Permission for RBAC
- Middleware aliases: `role`, `permission`, `role_or_permission`
- Roles/Permissions defined in `app/Enums/RolesEnum.php` and `PermissionsEnum.php`
- Seeded via `RolesAndPermissionsSeeder`

### User Model
- Uses UUIDs as primary key (`uuid` column, not `id`)
- Traits: `HasUuids`, `HasRoles`, `HasAuditLogs`, `Notifiable`
- Related models: `Address` (hasOne)

### Event Publishing
- `UserService::publishUserCreatedEvent()` publishes to Kafka topic `user.created`
- Uses `mateusjunges/laravel-kafka` package

### MCP Server
- MCP (Model Context Protocol) server at `/mcp/users`
- Feature-flagged via Laravel Pennant (`mcp-users-server`)
- Defined in `app/Mcp/Servers/UsersServer.php`

### Livewire Components
- Located in `app/Livewire/`
- User management: `Users/UsersList.php`, `UserCreate.php`, `UserEdit.php`
- Layout components: `Layouts/Navbar.php`, `Sidebar.php`, `Footer.php`

## Testing Patterns

### Integration Tests
- Located in `tests/Integration/`
- Extend `BaseWebTestCase` with `RefreshDatabase`
- Use test groups: `with-auth` (auto-login), `with-roles-and-permissions` (seeds RBAC)

### Test Helpers
- `getUrl(string $route, array $params)` - generate route URLs
- `getAuthorizationHeader(string $token)` - create Bearer auth header
- `createUser(string $email, string $password)` - factory helper

### Test Setup (from Pest.php)
```php
// Tests in 'with-auth' group automatically get $this->token
// Tests in 'with-roles-and-permissions' group get seeded roles
->group('with-auth', 'with-roles-and-permissions')
```

## Key Files

- `bootstrap/app.php` - Middleware, exceptions, routing configuration
- `app/Exceptions/ApiExceptionHandler.php` - Custom API error handling
- `database/seeders/RolesAndPermissionsSeeder.php` - RBAC seed data
