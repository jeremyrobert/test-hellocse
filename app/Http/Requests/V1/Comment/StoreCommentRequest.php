<?php

namespace App\Http\Requests\V1\Comment;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      schema="StoreCommentRequest",
 *      title="Store Comment Request",
 *      description="Store Comment request body data",
 *      required={"content"}
 *
 *      @OA\Property(
 *            property="content",
 *            type="string",
 *            example="This is a comment",
 *            description="The content of the comment"
 *      )
 * )
 */
class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', [Comment::class, $this->profile]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:255'],
        ];
    }
}
