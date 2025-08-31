<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the service providers for this category.
     */
    public function serviceProviders(): HasMany
    {
        return $this->hasMany(ServiceProvider::class);
    }

    /**
     * Get the active service providers for this category.
     */
    public function activeServiceProviders(): HasMany
    {
        return $this->hasMany(ServiceProvider::class)->where('is_active', true);
    }

    /**
     * Scope to get categories with active service providers.
     */
    public function scopeWithActiveProviders($query)
    {
        return $query->whereHas('serviceProviders', function ($q) {
            $q->where('is_active', true);
        });
    }
}
