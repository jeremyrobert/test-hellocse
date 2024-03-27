<?php

namespace App\Http\Requests\V1\Profile;

use App\Enums\StatusEnum;
use App\Enums\TokenAbility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="UpdateProfileRequest",
 *     title="Update Profile Request",
 *     description="Request structure for update a profile.",
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
class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan(TokenAbility::ACCESS_API->value);
    }

    /**
     * Prepare status field for validation.
     */
    public function prepareForValidation(): void
    {
        if ($this->status) {
            $this->merge([
                'status' => Str::lower($this->status),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'last_name' => ['string', 'max:255'],
            'first_name' => ['string', 'max:255'],
            'image' => ['image', 'max:2048'],
            'status' => [Rule::enum(StatusEnum::class)],
        ];
    }
}
