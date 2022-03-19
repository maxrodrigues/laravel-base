<?php

namespace App\Repository\Eloquent;

use App\Repository\EloquentBaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentBaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all models.
     *
     * @param array $columns
     * @param array $relations
     *
     * @return Collection
     */
    public function all(array $columns = ["*"], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    /**
     * Get all trashed models.
     *
     * @return Collection
     */
    public function allTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    /**
     * Find model by id.
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     *
     * @return Model|null
     */
    public function findById(int $modelId, array $columns = ["*"], array $relations = [], array $appends = []): ?Model
    {
        return $this->model->select($columns)->with($relations)->findOrfail($modelId)->append($appends);
    }

    /**
     * Find trashed model by id.
     * @param int $modelId
     *
     * @return Model|null
     */
    public function findTrashedById(int $modelId): ?Model
    {
        return $this->model->withTrashed()->findOrFail($modelId);
    }

    /**
     * Find only trashed model by id.
     * @param int $modelId
     *
     * @return Model|null
     */
    public function findOnlyTrashedById(int $modelId): ?Model
    {
        return $this->model->onlyTrashed()->findOrFail($modelId);
    }

    /**
     * Create model.
     * @param array $payload
     *
     * @return Model|null
     */
    public function create(array $payload): ?Model
    {
        $model = $this->model->create($payload);
        return $model;
    }

    /**
     * Update existing model.
     * @param int $modelId
     * @param array $payload
     *
     * @return bool
     */
    public function update(int $modelId, array $payload): bool
    {
        $model = $this->model->findById($modelId);
        return $model->update($payload);
    }

    /**
     * Delete model by id.
     * @param int $modelId
     *
     * @return bool
     */
    public function deleteById(int $modelId): bool
    {
        return $this->model->findById($modelId)->delete();
    }

    /**
     * Restore by model id.
     * @param int $modelId
     *
     * @return bool
     */
    public function restoreById(int $modelId): bool
    {
        return $this->model->findOnlyTrashedById($modelId)->restore();
    }

    /**
     * Permanently delete model by id.
     * @param int $modelId
     *
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool
    {
        return $this->mode->findTrashedById($modelId)->forceDelete();
    }
}
