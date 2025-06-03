<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\RolesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $uuid
 * @property string $first_name
 * @property string $last_name
 * @property string $role
 * @property string $phone
 * @property string $email
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \App\Http\Resources\AddressResource $address
 *
 * @method getRoleNames()
 * @method getAllPermissions()
 */
class UserResource extends JsonResource
{
    public function __construct($resource, private readonly bool $isAuthUser = false)
    {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'roles' => $this->whenLoaded('roles', fn () => $this->getRoleNames()),
            'permissions' => $this->when(
                $request->user()?->hasRole(RolesEnum::ADMIN->value) || $this->isAuthUser,
                $this->whenLoaded('permissions', fn () => $this->getAllPermissions()->pluck('name')),
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'address' => new AddressResource($this->whenLoaded('address')),
        ];
    }
}
