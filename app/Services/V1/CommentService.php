<?php

namespace App\Services\V1;

use App\Models\Comment;
use App\Models\Profile;

class CommentService
{
    /**
     * Store a new comment with the given data.
     *
     * @param  array<string, mixed>  $data
     */
    public function store(Profile $profile, array $data): Comment
    {
        return $profile->comments()->create([
            'administrator_id' => auth()->id(),
            ...$data,
        ]);
    }
}
