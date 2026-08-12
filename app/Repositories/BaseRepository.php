<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @class BaseRepository
 *
 * @package App\Repositories
 */
abstract class BaseRepository
{
    /**
     * The model builder instance.
     */
    protected Builder $model;

    /**
     * __construct
     * BaseRepository constructor.
     *
     * @param Builder $model
     */
    public function __construct(Builder $model)
    {
        $this->model = $model;
    }

    /**
     * create
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * find
     *
     * @param int|string $id
     * @return Model|null
     */
    public function find(int|string $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * update
     *
     * @param Model $model
     * @param array $attributes
     * @return bool
     */
    public function update(Model $model, array $attributes): bool
    {
        return $model->update($attributes);
    }

    /**
     * delete
     *
     * @param Model $model
     * @return bool|null
     */
    public function delete(Model $model): ?bool
    {
        return $model->delete();
    }

    /**
     * paginate
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }
}
