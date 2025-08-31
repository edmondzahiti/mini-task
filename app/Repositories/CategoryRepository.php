<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    /**
     * Get all categories with active service providers count
     */
    public function getAllWithActiveProvidersCount(): Collection
    {
        return $this->model->select(['id', 'name', 'slug'])
            ->withActiveProviders()
            ->withCount(['serviceProviders' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get category by ID
     */
    public function getById(int $id): ?Category
    {
        return $this->model->select(['id', 'name', 'slug'])->find($id);
    }
}
