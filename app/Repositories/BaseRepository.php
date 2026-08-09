<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

class BaseRepository
{
    /**
     * The model instance.
     *
     * @var Builder
     */
    protected Builder $model;

    /**
     * BaseRepository constructor.
     *
     * @param Builder $model
     */
    public function __construct(Builder $model)
    {
        $this->model = $model;
    }
}
