<?php

namespace App\Repositories;

use App\Models\ServiceProvider;
use Illuminate\Database\Eloquent\Collection;

class ServiceProviderRepository
{
    protected $model;

    public function __construct(ServiceProvider $model)
    {
        $this->model = $model;
    }

    /**
     * Get all active service providers with category relationship
     */
    public function getAllActiveWithCategory(): Collection
    {
        return $this->model->select([
                'id', 'name', 'slug', 'short_description', 'logo_path', 
                'category_id', 'website_url', 'contact_email', 'contact_phone'
            ])
            ->active()
            ->with(['category:id,name,slug'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get service providers filtered by category
     */
    public function getByCategory(int $categoryId): Collection
    {
        return $this->model->select([
                'id', 'name', 'slug', 'short_description', 'logo_path', 
                'category_id', 'website_url', 'contact_email', 'contact_phone'
            ])
            ->active()
            ->with(['category:id,name,slug'])
            ->byCategory($categoryId)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get service provider by slug with category
     */
    public function getBySlug(string $slug): ?ServiceProvider
    {
        return $this->model->select([
                'id', 'name', 'slug', 'short_description', 'description', 'logo_path', 
                'category_id', 'website_url', 'contact_email', 'contact_phone'
            ])
            ->active()
            ->with(['category:id,name,slug'])
            ->where('slug', $slug)
            ->first();
    }
}
