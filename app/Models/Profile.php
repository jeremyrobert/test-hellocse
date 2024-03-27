<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperProfile
 */
class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'administrator_id',
        'first_name',
        'image',
        'status',
    ];

    /**
     * Get the administrator that owns the profile.
     *
     * @return BelongsTo<Administrator, Profile>
     */
    public function administrator(): BelongsTo
    {
        return $this->belongsTo(Administrator::class);
    }
}
