<?php

namespace App\Implementations\Repositories;

use App\Interfaces\Repositories\IRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class Repository implements IRepository
{
  protected Model $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function model(): Model
  {
    return $this->model;
  }

  public function query(): Builder
  {
    return $this->model->newQuery();
  }

  protected function applyFilters(Builder $query, array $filters = []): Builder
  {
    // where
    if (!empty($filters['where'])) {
      foreach ($filters['where'] as $field => $value) {
        is_array($value)
          ? $query->whereIn($field, $value)
          : $query->where($field, $value);
      }
    }

    // with
    if (!empty($filters['with'])) {
      $query->with($filters['with']);
    }

    // order
    if (!empty($filters['orderBy'])) {
      foreach ($filters['orderBy'] as $field => $direction) {
        $query->orderBy($field, $direction);
      }
    }

    // select
    if (!empty($filters['select'])) {
      $query->select($filters['select']);
    }

    return $query;
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->applyFilters($this->query(), $filters);
    return $query->get();
  }

  public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
  {
    $query = $this->applyFilters($this->query(), $filters);
    return $query->paginate($perPage);
  }

  public function find(int|string $id): ?Model
  {
    return $this->model->find($id);
  }

  public function findBy(array $criteria): Collection
  {
    return $this->applyFilters($this->query(), [
      'where' => $criteria
    ])->get();
  }

  public function create(array $data): Model
  {
    return $this->model->create($data);
  }

  public function update(int|string $id, array $data): Model
  {
    $model = $this->find($id);

    if (!$model) {
      throw new \Exception("Model not found");
    }

    $model->update($data);

    return $model;
  }

  public function delete(int|string $id): bool
  {
    return $this->find($id)?->delete() ?? false;
  }

  public function count(array $criteria = []): int
  {
    return $this->applyFilters($this->query(), [
      'where' => $criteria
    ])->count();
  }
}
