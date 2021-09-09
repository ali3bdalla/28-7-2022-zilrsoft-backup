<?php

namespace App\Repository\Eloquent;

use App\Repository\BaseRepositoryContract;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryContract
{

    /**
     * @var Model
     */
    protected Model $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }
}
