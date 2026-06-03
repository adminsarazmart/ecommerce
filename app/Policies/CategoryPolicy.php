<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('category.list');
    }

    public function view(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('category.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('category.create');
    }

    public function update(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('category.update');
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('category.delete');
    }

    public function restore(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('category.restore');
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('category.force-delete');
    }
}
