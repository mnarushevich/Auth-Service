<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="DeletePermission",
 *     title="Delete Permission",
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
class DeletePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'guard_name' => ['required', 'in:web,api'],
        ];
    }
}
