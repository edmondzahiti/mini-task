<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    protected $repository;
    protected $cacheTtl = 3600; // 1 hour

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all categories with active providers count and caching
     */
    public function getAllWithActiveProvidersCount(): Collection
    {
        return Cache::remember('api_categories_with_counts', $this->cacheTtl, function () {
            return $this->repository->getAllWithActiveProvidersCount();
        });
    }

    /**
     * Get category by ID
     */
    public function getById(int $id)
    {
        return $this->repository->getById($id);
    }
}
