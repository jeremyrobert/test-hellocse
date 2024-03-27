<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\Profile\StoreProfileRequest;
use App\Http\Resources\Api\V1\Profile\ProfileResource;
use App\Services\V1\FileUploadService;
use App\Services\V1\ProfileService;

/**
 * @OA\Tag(
 *     name="Profile",
 *     description="Group of endpoints for managing profiles."
 * )
 */
class ProfileController
{
    public function __construct(
        protected ProfileService $profileService,
        protected FileUploadService $fileUploadService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/profile",
     *     tags={"Profile"},
     *     summary="Store a new profile.",
     *     description="Store a new profile with the given data.",
     *     security={{"access_token": {}}},
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *               mediaType="multipart/form-data",
     *               @OA\Schema(ref="#/components/schemas/StoreProfileRequest")
     *          )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful login"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Unprocessable Content"),
     *     @OA\Response(response=429, description="Too Many Requests"),
     * )
     */
    public function store(StoreProfileRequest $request): ProfileResource
    {
        $data = $request->validated();
        $data['image'] = $this->fileUploadService->store($request->validated('image'), 'images');
        $profile = $this->profileService->store($data);

        return ProfileResource::make($profile);
    }
}
