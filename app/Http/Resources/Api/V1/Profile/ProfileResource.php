<?php

namespace App\Http\Resources\Api\V1\Profile;

use App\Http\Resources\Api\V1\Comment\CommentResource;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Profile
 *
 * @OA\Schema(
 *     schema="ProfileResource",
 *     title="Profile Resource",
 *     description="Profile resource",
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="The unique identifier of the profile"
 *     ),
 *     @OA\Property(
 *          property="administrator_id",
 *          type="integer",
 *          description="The unique identifier of the administrator"
 *     ),
 *     @OA\Property(
 *          property="last_name",
 *          type="string",
 *          description="The last name of the profile"
 *     ),
 *     @OA\Property(
 *          property="first_name",
 *          type="string",
 *          description="The first name of the profile"
 *     ),
 *     @OA\Property(
 *          property="image",
 *          type="string",
 *          description="The image of the profile"
 *     ),
 *     @OA\Property(
 *          property="created_at",
 *          type="string",
 *          format="date-time",
 *          description="The date and time of the profile creation"
 *     ),
 *     @OA\Property(
 *          property="comments",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/CommentResource"),
 *          description="The comments of the profile"
 *     ),
 * )
 */
class ProfileResource extends JsonResource
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
            'administrator_id' => $this->administrator_id,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'image' => $this->image,
            'status' => $this->when(Auth::check(), $this->status),
            'created_at' => $this->created_at->toDateTimeString(),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
