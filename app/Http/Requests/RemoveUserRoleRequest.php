<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="RemoveUserRole",
 *     title="Remove User Role",
 *     required={"role_name"},
 *
 * 	@OA\Property(
 *         property="role_name",
 *         type="string"
 *     ),
 * )
 */
class RemoveUserRoleRequest extends FormRequest
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
