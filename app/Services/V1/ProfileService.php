<?php

namespace App\Services\V1;

use App\Models\Profile;

class ProfileService
{
    /**
     * Store a new profile with the given data.
     *
     * @param  array<string, mixed>  $data
     */
    public function store(array $data): Profile
    {
        return Profile::create([
            'administrator_id' => auth()->id(),
            ...$data,
        ]);
    }

    /**
     * Update the profile with the given data.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Profile $profile, array $data): Profile
    {
        return tap($profile)->update($data);
    }

    /**
     * Delete the profile.
     */
    public function destroy(Profile $profile): bool
    {
        return $profile->delete();
    }
}
