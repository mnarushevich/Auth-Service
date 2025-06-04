<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use App\Enums\RolesEnum;
use App\Models\User;
use Database\Factories\UserFactory;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Integration\BaseWebTestCase;

const TEST_USER_EMAIL = 'test@test.com';

pest()->extend(BaseWebTestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Integration');

pest()->beforeEach(function (): void {
    $this->mockPass = fake()->password();
    $this->mockEmail = TEST_USER_EMAIL;
    $this->user = UserFactory::new()->create(
        ['email' => $this->mockEmail, 'password' => Hash::make($this->mockPass)]
    );
    $this->user->address()->create(['country' => fake()->country]);

    if (in_array('with-auth', test()->groups())) {
        $response = $this->postJson(
            getUrl(BaseWebTestCase::LOGIN_ROUTE_NAME),
            ['email' => $this->user->email, 'password' => $this->mockPass]
        )->decodeResponseJson();

        $this->token = $response['access_token'];
    }

    if (in_array('with-roles-and-permissions', test()->groups())) {
        $this->artisan('db:seed', ['--class' => RolesAndPermissionsSeeder::class]);
        $this->user->assignRole(RolesEnum::USER);
    }
})->group('auth')->in('Integration');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', fn () => $this->toBe(1));

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function createUser(string $email, string $password): User
{
    return UserFactory::new()->create(
        ['email' => $email, 'password' => Hash::make($password)]
    );
}
