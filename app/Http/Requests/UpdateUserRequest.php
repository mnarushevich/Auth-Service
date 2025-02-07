<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="UpdateUser",
 *     title="Update User",
 *
 * 	@OA\Property(
 *         property="first_name",
 *         type="string"
 *     ),
 * 	@OA\Property(
 *         property="last_name",
 *         type="string"
 *     ),
 *  @OA\Property(
 *         property="country",
 *         type="string"
 *    ),
 *  @OA\Property(
 *         property="phone",
 *         type="string"
 *    ),
 * )
 */
class UpdateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user),
            ],
            'first_name' => 'sometimes|required',
            'last_name' => 'sometimes|required',
            'country' => 'sometimes|required',
            'role' => 'sometimes|required',
            'phone' => 'sometimes|required',
        ];
    }
}
