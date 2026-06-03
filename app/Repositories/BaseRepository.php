<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    public function find(int $id, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->with($relations)->find($id, $columns);
    }

    public function findOrFail(int $id, array $columns = ['*'], array $relations = []): Model
    {
        return $this->model->with($relations)->findOrFail($id, $columns);
    }

    public function findBy(string $field, $value, array $columns = ['*']): ?Model
    {
        return $this->model->where($field, '=', $value)->first($columns);
    }

    public function findAllBy(string $field, $value, array $columns = ['*']): Collection
    {
        return $this->model->where($field, '=', $value)->get($columns);
    }

    public function findWhereIn(string $field, array $values, array $columns = ['*']): Collection
    {
        return $this->model->whereIn($field, $values)->get($columns);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $record = $this->findOrFail($id);
        $record->update($data);
        return $record->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->findOrFail($id)->delete();
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = [])
    {
        return $this->model->with($relations)->paginate($perPage, $columns);
    }

    public function with(array $relations): Builder
    {
        return $this->model->with($relations);
    }

    public function orderBy(string $column, string $direction = 'asc'): Builder
    {
        return $this->model->orderBy($column, $direction);
    }

    public function count(): int
    {
        return $this->model->count();
    }

    public function exists(string $field, $value): bool
    {
        return $this->model->where($field, '=', $value)->exists();
    }

    public function first(array $columns = ['*']): ?Model
    {
        return $this->model->first($columns);
    }

    public function pluck(string $value, string $key = null): Collection
    {
        return $this->model->pluck($value, $key);
    }

    public function updateOrCreate(array $conditions, array $data): Model
    {
        return $this->model->updateOrCreate($conditions, $data);
    }

    public function insert(array $data): bool
    {
        return $this->model->insert($data);
    }

    public function chunk(int $size, callable $callback): void
    {
        $this->model->chunk($size, $callback);
    }

    protected function applyConditions(Builder $query, array $conditions): Builder
    {
        foreach ($conditions as $field => $value) {
            if (is_array($value)) {
                [$operator, $val] = $value;
                $query->where($field, $operator, $val);
            } else {
                $query->where($field, '=', $value);
            }
        }
        return $query;
    }

    protected function handleRelations(Builder $query, array $relations): Builder
    {
        return $query->with($relations);
    }
}
