<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @class Image
 *
 * @package App\Models
 */
class Image extends Model
{
    protected $fillable = [
        'path',
        'original_name',
        'hash',
        'size',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * users
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
