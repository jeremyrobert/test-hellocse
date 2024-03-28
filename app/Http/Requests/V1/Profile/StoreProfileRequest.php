<?php

namespace App\Http\Requests\V1\Profile;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="StoreProfileRequest",
 *     title="Store Profile Request",
 *     description="Request structure for storing a new profile.",
 *     required={"last_name", "first_name", "image", "status"},
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         example="Doe",
 *         description="The last name of the profile"
 *     ),
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         example="John",
 *         description="The first name of the profile"
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="base64",
 *         description="The image of the profile"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"inactive", "pending", "active"},
 *         description="The status of the profile"
 *     )
 * )
 */
class StoreProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare status field for validation.
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'status' => Str::lower($this->status),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:2048'],
            'status' => ['required', Rule::enum(StatusEnum::class)],
        ];
    }
}
