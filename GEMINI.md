# Project Overview

This is a Laravel-based authentication microservice. It provides user authentication and management, including JWT-based authentication, role-based access control (RBAC), and Kafka integration.

## Key Technologies

*   **Backend:** Laravel (PHP)
*   **Authentication:** JWT (`tymon/jwt-auth`)
*   **Authorization:** RBAC (`spatie/laravel-permission`)
*   **Database:** MySQL
*   **Caching:** Redis
*   **Messaging:** Apache Kafka (`mateusjunges/laravel-kafka`)
*   **Admin Panel:** Filament
*   **Frontend:** Livewire
*   **Testing:** Pest
*   **Code Quality:** Pint, Larastan, Rector

## Building and Running

The project is set up to be run with [Laravel Herd](https://herd.laravel.com/) and Docker Compose.

### Initial Setup

1.  Run `make install_and_start` to install dependencies, set up the environment, and start the services. This will:
    *   Isolate the PHP version to 8.4 using Herd.
    *   Install Composer dependencies.
    *   Create a `.env` file from `.env.example`.
    *   Start the Docker containers for MySQL, Redis, Kafka, and Mailpit.
    *   Generate an application key and a JWT secret.

### Running the Application

*   Use `make up` to start the Docker containers.
*   Use `make stop` to stop the Docker containers.
*   The application will be available at `http://localhost:8701` by default.

### Running Tests

1.  Set up the testing environment with `make setup-tests`.
2.  Run the tests with `make run-tests`.

### Useful Commands

*   `make rebuild`: Rebuild the app container.
*   `make exec`: Execute a shell inside the app container.
*   `make front`: Run the frontend development server.
*   `make db-seed`: Seed the database.
*   `make api-docs-generate`: Generate API documentation.

## Development Conventions

### Testing

The project uses Pest for testing. Test files are located in the `tests` directory. The test suite can be run with the `make run-tests` command.

### Code Style

The project uses `laravel/pint` for code styling. You can run the linter with `composer test:lint` and fix issues with `composer test:lint:fix`.

### Static Analysis

The project uses `larastan/larastan` for static analysis. You can run the analysis with `composer test:types`.
