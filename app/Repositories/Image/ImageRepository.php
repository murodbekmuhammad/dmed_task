<?php

namespace App\Repositories\Image;

use App\Models\Image;
use App\Repositories\BaseRepository;

/**
 * Class ImageRepository
 */
class ImageRepository extends BaseRepository
{
    /**
     * ImageRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Image::query());
    }
}
