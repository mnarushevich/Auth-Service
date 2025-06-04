<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="AssignUserRole",
 *     title="Assign User Role",
 *     required={"role_name"},
 *
 * 	@OA\Property(
 *         property="role_name",
 *         type="string"
 *     ),
 * )
 */
class AssignUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_name' => ['required', 'string'],
        ];
    }
}
