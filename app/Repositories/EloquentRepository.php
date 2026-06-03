<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EloquentRepository extends BaseRepository
{
    public function applyScope(callable $scope): self
    {
        $this->model = $scope($this->model);
        return $this;
    }

    public function whereHas(string $relation, callable $callback): Builder
    {
        return $this->model->whereHas($relation, $callback);
    }

    public function whereDoesntHave(string $relation, callable $callback = null): Builder
    {
        return $this->model->whereDoesntHave($relation, $callback);
    }

    public function has(string $relation, string $operator = '>=', int $count = 1): Builder
    {
        return $this->model->has($relation, $operator, $count);
    }

    public function doesntHave(string $relation): Builder
    {
        return $this->model->doesntHave($relation);
    }

    public function withCount(array $relations): Builder
    {
        return $this->model->withCount($relations);
    }

    public function withSum(array $relations): Builder
    {
        return $this->model->withSum($relations);
    }

    public function withAvg(array $relations): Builder
    {
        return $this->model->withAvg($relations);
    }

    public function whereBetween(string $column, array $range): Builder
    {
        return $this->model->whereBetween($column, $range);
    }

    public function whereDateBetween(string $column, array $range): Builder
    {
        return $this->model->whereDate($column, '>=', $range[0])
            ->whereDate($column, '<=', $range[1]);
    }

    public function search(string $term, array $columns): Builder
    {
        $query = $this->model;
        foreach ($columns as $column) {
            $query->orWhere($column, 'LIKE', "%{$term}%");
        }
        return $query;
    }

    public function latest(string $column = 'created_at'): Builder
    {
        return $this->model->latest($column);
    }

    public function oldest(string $column = 'created_at'): Builder
    {
        return $this->model->oldest($column);
    }

    public function newQuery(): Builder
    {
        return $this->model->newQuery();
    }

    public function model(): Model
    {
        return $this->model;
    }
}
