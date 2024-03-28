<?php

namespace App\Policies;

use App\Models\Administrator;
use App\Models\Profile;

class ProfilePolicy
{
    /**
     * Only the profile owner can update it.
     */
    public function update(Administrator $administrator, Profile $profile): bool
    {
        return $administrator->id === $profile->administrator_id;
    }

    /**
     * Only the profile owner can delete it.
     */
    public function delete(Administrator $administrator, Profile $profile): bool
    {
        return $administrator->id === $profile->administrator_id;
    }
}
