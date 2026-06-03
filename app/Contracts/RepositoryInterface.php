<?php

namespace App\Contracts;

interface RepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = []);
    public function find(int $id, array $columns = ['*'], array $relations = []);
    public function findOrFail(int $id, array $columns = ['*'], array $relations = []);
    public function findBy(string $field, $value, array $columns = ['*']);
    public function findAllBy(string $field, $value, array $columns = ['*']);
    public function findWhereIn(string $field, array $values, array $columns = ['*']);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = []);
    public function with(array $relations);
    public function orderBy(string $column, string $direction = 'asc');
    public function count();
    public function exists(string $field, $value);
    public function first(array $columns = ['*']);
    public function pluck(string $value, string $key = null);
    public function updateOrCreate(array $conditions, array $data);
    public function insert(array $data);
    public function chunk(int $size, callable $callback);
}
