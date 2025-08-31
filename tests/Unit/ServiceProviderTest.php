<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ServiceProvider;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_provider_can_be_created()
    {
        $category = Category::factory()->create(['name' => 'Technology']);

        $provider = ServiceProvider::factory()->create([
            'name' => 'Test Provider',
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('service_providers', [
            'name' => 'Test Provider',
        ]);
        
        // Check that slug was generated
        $this->assertNotNull($provider->slug);
    }

    public function test_service_provider_slug_is_auto_generated()
    {
        $category = Category::factory()->create(['name' => 'Technology']);

        $provider = ServiceProvider::factory()->create([
            'name' => 'Test Service Provider',
            'category_id' => $category->id,
        ]);

        // Check that slug was generated and is not empty
        $this->assertNotNull($provider->slug);
        $this->assertNotEmpty($provider->slug);
        $this->assertIsString($provider->slug);
    }

    public function test_service_provider_belongs_to_category()
    {
        $category = Category::factory()->create(['name' => 'Technology']);

        $provider = ServiceProvider::factory()->create([
            'name' => 'Test Provider',
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(Category::class, $provider->category);
        $this->assertEquals('Technology', $provider->category->name);
    }

    public function test_active_scope_returns_only_active_providers()
    {
        $category = Category::factory()->create(['name' => 'Technology']);

        ServiceProvider::factory()->create([
            'name' => 'Active Provider',
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        ServiceProvider::factory()->inactive()->create([
            'name' => 'Inactive Provider',
            'category_id' => $category->id,
        ]);

        $activeProviders = ServiceProvider::active()->get();

        $this->assertEquals(1, $activeProviders->count());
        $this->assertEquals('Active Provider', $activeProviders->first()->name);
    }

    public function test_by_category_scope_filters_correctly()
    {
        $techCategory = Category::factory()->create(['name' => 'Technology']);
        $healthCategory = Category::factory()->create(['name' => 'Healthcare']);

        ServiceProvider::factory()->create([
            'name' => 'Tech Provider',
            'category_id' => $techCategory->id,
        ]);

        ServiceProvider::factory()->create([
            'name' => 'Health Provider',
            'category_id' => $healthCategory->id,
        ]);

        $techProviders = ServiceProvider::byCategory($techCategory->id)->get();

        $this->assertEquals(1, $techProviders->count());
        $this->assertEquals('Tech Provider', $techProviders->first()->name);
    }
}
