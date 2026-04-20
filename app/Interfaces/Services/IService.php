<?php

namespace App\Interfaces\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IService
{
  public function all(array $filters = []): Collection;

  public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

  public function find(int|string $id): ?Model;

  public function findBy(array $criteria): Collection;

  public function create(array $data): Model;

  public function update(int|string $id, array $data): Model;

  public function delete(int|string $id): bool;

  public function count(array $criteria = []): int;
}
