<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }

    public function getTree(): Collection
    {
        return $this->model->whereNull('parent_id')
            ->with('children.children')
            ->orderBy('sort_order')
            ->get();
    }

    public function getActive(): Collection
    {
        return $this->model->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getByParent(?int $parentId = null): Collection
    {
        return $this->model->where('parent_id', $parentId)
            ->orderBy('sort_order')
            ->get();
    }
}
