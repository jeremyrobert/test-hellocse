<?php

namespace App\Http\Resources\Api\V1\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Comment
 *
 * @OA\Schema(
 *     schema="CommentResource",
 *     title="Comment Resource",
 *     description="Comment resource",
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="The unique identifier of the profile"
 *     ),
 *     @OA\Property(
 *          property="profile_id",
 *          type="integer",
 *          description="The unique identifier of the profile"
 *     ),
 *     @OA\Property(
 *          property="administrator_id",
 *          type="integer",
 *          description="The unique identifier of the administrator"
 *     ),
 *     @OA\Property(
 *          property="content",
 *          type="string",
 *          description="The content of the comment"
 *     ),
 *     @OA\Property(
 *          property="created_at",
 *          type="string",
 *          format="date-time",
 *          description="The date and time the comment was created"
 *     ),
 * )
 */
class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'profile_id' => $this->profile_id,
            'administrator_id' => $this->administrator_id,
            'content' => $this->content,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
