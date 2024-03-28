<?php

namespace App\Http\Resources\Api\V1\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="CommentCollection",
 *     title="Comment Collection",
 *     description="Comment collection",
 *     @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/CommentResource"),
 *          description="The list of comments"
 *     ),
 * )
 */
class CommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request); /** @phpstan-ignore-line */
    }
}
