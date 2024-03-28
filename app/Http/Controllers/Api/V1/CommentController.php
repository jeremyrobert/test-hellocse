<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\Comment\StoreCommentRequest;
use App\Http\Resources\Api\V1\Comment\CommentResource;
use App\Models\Comment;
use App\Models\Profile;
use App\Services\V1\CommentService;

/**
 * @OA\Tag(
 *     name="Comments",
 *     description="Group of endpoints for managing comments."
 * )
 */
class CommentController
{
    public function __construct(protected CommentService $commentService) {}

    /**
     * @OA\Post(
     *     path="/api/v1/profiles/{profile}/comments",
     *     tags={"Comments"},
     *     summary="Store a new comment.",
     *     description="Store a new comment for the given profile.",
     *     security={{"access_token": {}}},
     *     @OA\Parameter(
     *          name="profile",
     *          in="path",
     *          description="The unique identifier of the profile",
     *          required=true,
     *          @OA\Schema(
     *               type="integer"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *               mediaType="multipart/form-data",
     *               @OA\Schema(ref="#/components/schemas/StoreCommentRequest")
     *          )
     *     ),
     *
     *     @OA\Response(
     *          response=201,
     *          description="Successful response: Comment created.",
     *          @OA\JsonContent(
     *               ref="#/components/schemas/CommentResource"
     *          )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Unprocessable Content"),
     *     @OA\Response(response=429, description="Too Many Requests"),
     * )
     */
    public function store(StoreCommentRequest $request, Profile $profile)
    {
        $comment = $this->commentService->store($profile, $request->validated());

        return CommentResource::make($comment);
    }
}
