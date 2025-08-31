<?php

namespace App\Services;

use App\Repositories\ServiceProviderRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ServiceProviderService
{
    protected $repository;
    public $cacheTtl = 300; // 5 minutes

    public function __construct(ServiceProviderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get cache TTL
     */
    public function getCacheTtl(): int
    {
        return $this->cacheTtl;
    }

    /**
     * Get all service providers with caching
     */
    public function getAllWithCaching(?int $categoryId = null): Collection
    {
        $cacheKey = 'api_service_providers_' . ($categoryId ?? 'all');
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($categoryId) {
            if ($categoryId) {
                return $this->repository->getByCategory($categoryId);
            }
            
            return $this->repository->getAllActiveWithCategory();
        });
    }

    /**
     * Get service provider by slug with caching
     */
    public function getBySlugWithCaching(string $slug)
    {
        $cacheKey = 'api_service_provider_' . $slug;
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($slug) {
            return $this->repository->getBySlug($slug);
        });
    }
}
