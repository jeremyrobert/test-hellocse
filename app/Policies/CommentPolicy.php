<?php

namespace App\Policies;

use App\Models\Administrator;
use App\Models\Profile;

class CommentPolicy
{
    /**
     * Only one comment per administrator per profile is allowed.
     */
    public function create(Administrator $administrator, Profile $profile): bool
    {
        return $administrator->comments()->where('profile_id', $profile->id)->doesntExist();
    }
}
