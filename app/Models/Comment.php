<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'administrator_id',
        'profile_id',
    ];

    /**
     * Get the profile that owns the comment.
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * Get the administrator that owns the comment.
     */
    public function administrator()
    {
        return $this->belongsTo(Administrator::class);
    }
}
