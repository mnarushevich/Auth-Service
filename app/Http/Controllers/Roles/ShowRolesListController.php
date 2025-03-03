<?php

declare(strict_types=1);

namespace App\Http\Controllers\Roles;

use App\Enums\GuardsEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\RolesCollection;
use App\Models\Role;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/roles",
 *     summary="Get a list of roles",
 *     operationId="rolesList",
 *     tags={"Roles"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Response(response=200, description="Successful operation"),
 *     @OA\Response(response=401, description="Unauthenticated")
 * )
 */
class ShowRolesListController extends Controller
{
    public function __invoke(Request $request): RolesCollection
    {
        $roles = Role::query();

        if ($request->has('guard_name') && in_array($request->input('guard_name'), GuardsEnum::all())) {
            $roles->where('guard_name', $request->input('guard_name'));
        }

        return new RolesCollection($roles->paginate(15));
    }
}
