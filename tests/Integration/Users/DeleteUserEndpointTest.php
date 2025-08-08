<?php

declare(strict_types=1);

namespace Tests\Integration\Auth;

use App\Enums\RolesEnum;
use App\Http\Resources\UserResource;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use tests\Integration\BaseWebTestCase;

describe('DELETE /users/{uuid}', function (): void {
    it('rejects for unauthorized', function (): void {
        Cache::expects('forget')->never();
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_USER_BY_UUID_ROUTE_NAME, ['user' => 'test']),
        )
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthenticated.',
                ]
            );
    });

    it('returns not found for invalid UUID', function (): void {
        Cache::expects('forget')->never();
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_USER_BY_UUID_ROUTE_NAME, ['user' => 'test']),
            headers: getAuthorizationHeader($this->token)
        )
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(
                [
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Not found.',
                ]
            );
    })->group('with-auth');

    it('deletes user with valid UUID', function (): void {
        Cache::shouldReceive('remember')
            ->twice()
            ->andReturnUsing(function (): UserResource {
                static $call = 0;
                $call += 1;
                if ($call === 1) {
                    return new UserResource($this->user);
                }

                throw new ModelNotFoundException;
            });
        Cache::expects('forget')->once()->with(sprintf('user-%s', $this->user->uuid));

        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )->assertOk();

        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )->assertOk();

        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )->assertStatus(Response::HTTP_NOT_FOUND);
    })->group('with-auth');

    it('denied to delete another user for non-admin', function (): void {
        Cache::expects('remember')->once()->andReturn(new UserResource($this->user));
        Cache::expects('forget')->never();
        $this->getJson(
            getUrl(BaseWebTestCase::GET_USER_BY_UUID_ROUTE_NAME, ['user' => $this->user->uuid]),
            headers: getAuthorizationHeader($this->token)
        )->assertOk();

        $newUser = UserFactory::new()->withUserRole()->create();
        expect($newUser->getRoleNames())->toContain(RolesEnum::USER->value);
        $this->deleteJson(
            getUrl(BaseWebTestCase::DELETE_USER_BY_UUID_ROUTE_NAME, ['user' => $newUser->uuid]),
            headers: getAuthorizationHeader($this->token)
        )->assertStatus(Response::HTTP_FORBIDDEN);
    })->group('with-auth');
})->group('users', 'with-roles-and-permissions');
