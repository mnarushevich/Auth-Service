<?php

declare(strict_types=1);

namespace App\Http\Controllers\Permissions;

use App\Enums\GuardsEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionsCollection;
use App\Models\Permission;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/permissions",
 *     summary="Get a list of permissions",
 *     operationId="permissionsList",
 *     tags={"Permissions"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Response(response=200, description="Successful operation"),
 *     @OA\Response(response=401, description="Unauthenticated")
 * )
 */
class ShowPermissionListController extends Controller
{
    public function __invoke(Request $request): PermissionsCollection
    {
        $permissions = Permission::query();

        if ($request->has('guard_name') && in_array($request->input('guard_name'), GuardsEnum::all())) {
            $permissions->where('guard_name', $request->input('guard_name'));
        }

        if ($request->has('name')) {
            $permissions->where('name', $request->input('name'));
        }

        return new PermissionsCollection($permissions->paginate(15));
    }
}
