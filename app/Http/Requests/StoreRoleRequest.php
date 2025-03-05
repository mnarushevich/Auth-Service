<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CreateRole",
 *     title="Create Role",
 *     required={"name", "guard_name"},
 *
 * 	@OA\Property(
 *         property="name",
 *         type="string"
 *     ),
 * 	@OA\Property(
 *         property="guard_name",
 *         type="string"
 *     ),
 * )
 */
class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'guard_name' => ['required', 'in:web,api'],
        ];
    }
}
