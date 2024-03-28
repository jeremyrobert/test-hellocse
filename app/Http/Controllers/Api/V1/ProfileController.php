<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\Profile\StoreProfileRequest;
use App\Http\Requests\V1\Profile\UpdateProfileRequest;
use App\Http\Resources\Api\V1\Profile\ProfileCollection;
use App\Http\Resources\Api\V1\Profile\ProfileResource;
use App\Models\Profile;
use App\Services\V1\FileUploadService;
use App\Services\V1\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Tag(
 *     name="Profiles",
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
     * @OA\Get(
     *     path="/api/v1/profiles",
     *     tags={"Profiles"},
     *     summary="Get a list of active profiles.",
     *     description="Get a list of active profiles with pagination.",
     *     @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="Number of profiles per page",
     *          required=false,
     *          @OA\Schema(
     *               type="integer",
     *               default=5
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Current page number",
     *          required=false,
     *          @OA\Schema(
     *               type="integer",
     *               default=1
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="List of profiles",
     *          @OA\JsonContent(ref="#/components/schemas/ProfileCollection")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=429, description="Too Many Requests"),
     * )
     */
    public function index(Request $request): ProfileCollection
    {
        $limit = (int) $request->query('limit', '5');
        $profiles = Profile::active()->with('comments')->paginate($limit);

        return new ProfileCollection($profiles);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/profiles",
     *     tags={"Profiles"},
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
     *     @OA\Response(response=201, description="Profile created successfully"),
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

    /**
     * @OA\Put(
     *     path="/api/v1/profiles/{profile}",
     *     tags={"Profiles"},
     *     summary="Update a profile.",
     *     description="Update the profile with the given data.",
     *     security={{"access_token": {}}},
     *     @OA\Parameter(
     *          name="profile",
     *          in="path",
     *          required=true,
     *          description="The ID of the profile",
     *          @OA\Schema(
     *               type="integer",
     *          ),
     *     ),
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *               mediaType="multipart/form-data",
     *               @OA\Schema(ref="#/components/schemas/UpdateProfileRequest")
     *          )
     *     ),
     *
     *     @OA\Response(response=200, description="Profile updated successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=422, description="Unprocessable Content"),
     *     @OA\Response(response=429, description="Too Many Requests"),
     * )
     */
    public function update(UpdateProfileRequest $request, Profile $profile): ProfileResource|JsonResponse
    {
        $data = $request->validated();

        if ($request->has('image')) {
            $data['image'] = $this->fileUploadService->update($request->validated('image'), 'images', $profile->image);
        }

        $this->profileService->update($profile, $data);

        return ProfileResource::make($profile);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/profiles/{profile}",
     *     tags={"Profiles"},
     *     summary="Delete a profile.",
     *     description="Delete the profile with the given ID.",
     *     security={{"access_token": {}}},
     *     @OA\Parameter(
     *          name="profile",
     *          in="path",
     *          required=true,
     *          description="The ID of the profile",
     *          @OA\Schema(
     *               type="integer",
     *          ),
     *     ),
     *
     *     @OA\Response(response=204, description="Profile deleted successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=429, description="Too Many Requests"),
     * )
     */
    public function destroy(Profile $profile): JsonResponse
    {
        Gate::authorize('delete', $profile);

        $this->profileService->destroy($profile);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
