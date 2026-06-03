<?php

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Contracts\Validation\Validator;

abstract class BaseService implements ServiceInterface
{
    protected RepositoryInterface $repository;
    protected ?Validator $validator = null;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all(): \Illuminate\Support\Collection
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Model
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Model
    {
        DB::beginTransaction();
        try {
            $model = $this->repository->create($data);
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, array $data): Model
    {
        DB::beginTransaction();
        try {
            $model = $this->repository->update($id, $data);
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->repository->delete($id);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function paginate(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    protected function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    protected function commit(): void
    {
        DB::commit();
    }

    protected function rollback(): void
    {
        DB::rollBack();
    }

    protected function respondWithSuccess($data = null, string $message = 'Operation successful', int $code = 200): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'code' => $code,
        ];
    }

    protected function respondWithError(string $message = 'Operation failed', int $code = 400, $errors = null): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $code,
        ];
    }

    protected function dispatchEvent(string $event, array $payload = []): void
    {
        Event::dispatch(new $event(...$payload));
    }

    protected function logActivity(string $message, array $context = []): void
    {
        Log::info($message, $context);
    }
}
