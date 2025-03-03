## Managing RBAC in Auth Service

Auth Service uses Role-Based Access Control (RBAC) implementation based on `spatie/laravel-permission` package.

### Installation

1. **Install the package via Composer:**

    ```bash
    composer require spatie/laravel-permission
    ```

2. **Publish the migration and config file:**

    ```bash
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
    ```

3. **Run the migrations:**

    ```bash
    php artisan migrate
    ```

### Configuration

1. **Add the `Spatie\Permission\Traits\HasRoles` trait to your `User` model:**

    ```php
    <?php

    namespace App\Models;

    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Spatie\Permission\Traits\HasRoles;

    class User extends Authenticatable
    {
        use HasRoles;

        // Your model code...
    }
    ```

2. **Configure the `config/permission.php` file if needed.**

### Usage

1. **Creating Roles and Permissions:**

    ```bash
   php artisan permission:create-role admin api "create users|edit users|delete users|view users"
    ```

2. **Viewing existing roles/permissions:**

    ```bash
php artisan permission:show
    ```

### Additional Resources

- [Spatie Laravel Permission Documentation](https://spatie.be/docs/laravel-permission)
- [Laravel Authorization Documentation](https://laravel.com/docs/authorization)

This guide provides a basic overview of managing RBAC using the `spatie/laravel-permission` package. For more advanced usage and customization, refer to the official documentation.
