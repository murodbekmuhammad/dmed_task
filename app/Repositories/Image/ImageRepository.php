<?php

namespace App\Repositories\Image;

use App\Models\Image;
use App\Repositories\BaseRepository;

/**
 * @class ImageRepository
 *
 * @package App\Repositories\Image
 */
class ImageRepository extends BaseRepository
{
    /**
     * __construct
     * ImageRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Image::query());
    }
}
