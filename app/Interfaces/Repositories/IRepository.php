<?php

namespace App\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IRepository
{
  public function model(): Model;

  public function query(): Builder;

  public function all(array $filters = []): Collection;

  public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;

  public function find(int|string $id): ?Model;

  public function findBy(array $criteria): Collection;

  public function create(array $data): Model;

  public function update(int|string $id, array $data): Model;

  public function delete(int|string $id): bool;

  public function count(array $criteria = []): int;
}
