<?php

namespace App\Http\Resources\Api\V1\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="ProfileCollection",
 *     title="Profile Collection",
 *     description="Profile collection",
 *     @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/ProfileResource"),
 *          description="The list of profiles"
 *     ),
 * )
 */
class ProfileCollection extends ResourceCollection
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
