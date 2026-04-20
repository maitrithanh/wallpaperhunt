<?php

namespace App\Implementations\Services;

use App\Interfaces\Repositories\IRepository;
use App\Interfaces\Services\IService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class Service implements IService
{
  protected IRepository $repository;

  public function __construct(IRepository $repository)
  {
    $this->repository = $repository;
  }

  public function all(array $filters = []): Collection
  {
    return $this->repository->all($filters);
  }

  public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
  {
    return $this->repository->paginate($perPage, $filters);
  }

  public function find(int|string $id): ?Model
  {
    return $this->repository->find($id);
  }

  public function findBy(array $criteria): Collection
  {
    return $this->repository->findBy($criteria);
  }

  public function create(array $data): Model
  {
    return $this->repository->create($data);
  }

  public function update(int|string $id, array $data): Model
  {
    return $this->repository->update($id, $data);
  }

  public function delete(int|string $id): bool
  {
    return $this->repository->delete($id);
  }

  public function count(array $criteria = []): int
  {
    return $this->repository->count($criteria);
  }
}
