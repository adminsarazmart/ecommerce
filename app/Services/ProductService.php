<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService extends BaseService
{
    protected ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($repository);
    }

    public function createProduct(array $data, array $variants = [], array $images = [], array $attributes = []): Product
    {
        DB::beginTransaction();
        try {
            if (!isset($data['sku'])) {
                $data['sku'] = strtoupper(Str::random(8));
            }

            $product = $this->repository->create($data);

            if (!empty($variants)) {
                $product->variants()->createMany($variants);
            }

            if (!empty($images)) {
                foreach ($images as $image) {
                    $product->addMedia($image)->toMediaCollection('products');
                }
            }

            if (!empty($attributes)) {
                $product->attributes()->sync($attributes);
            }

            DB::commit();
            return $product->load(['variants', 'attributes', 'categories']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProduct(int $id, array $data): Product
    {
        $product = $this->repository->update($id, $data);
        return $product->fresh(['variants', 'attributes', 'categories', 'images']);
    }

    public function deleteProduct(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function searchProducts(string $term, int $perPage = 15)
    {
        return $this->repository->searchProducts($term, $perPage);
    }

    public function cloneProduct(int $id, array $overrides = []): Product
    {
        DB::beginTransaction();
        try {
            $original = $this->repository->findOrFail($id);
            $data = $original->toArray();
            unset($data['id'], $data['uuid'], $data['created_at'], $data['updated_at']);
            $data['name'] = $data['name'] . ' (Copy)';
            $data['sku'] = $data['sku'] . '-COPY';
            $data = array_merge($data, $overrides);

            $clone = $this->repository->create($data);

            foreach ($original->variants as $variant) {
                $variantData = $variant->toArray();
                unset($variantData['id'], $variantData['created_at'], $variantData['updated_at']);
                $clone->variants()->create($variantData);
            }

            $clone->categories()->sync($original->categories->pluck('id')->toArray());
            $clone->attributes()->sync($original->attributes->pluck('id')->toArray());

            DB::commit();
            return $clone->load(['variants', 'categories', 'attributes']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function bulkImport(array $products): array
    {
        $imported = [];
        $failed = [];

        foreach ($products as $index => $data) {
            try {
                $product = $this->createProduct($data);
                $imported[] = $product->id;
            } catch (\Exception $e) {
                $failed[] = ['index' => $index, 'error' => $e->getMessage()];
            }
        }

        return [
            'imported' => $imported,
            'failed' => $failed,
            'total' => count($products),
            'success_count' => count($imported),
            'failure_count' => count($failed),
        ];
    }

    public function getProductWithRelations(int $id, array $relations = []): ?Product
    {
        $defaultRelations = ['categories', 'variants', 'images', 'attributes', 'vendor', 'reviews'];
        $relations = array_merge($defaultRelations, $relations);
        return $this->repository->find($id, ['*'], $relations);
    }
}
