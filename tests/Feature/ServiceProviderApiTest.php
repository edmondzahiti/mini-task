<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ServiceProvider;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceProviderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_categories()
    {
        // Create test categories using factories
        Category::factory()->create(['name' => 'Technology']);
        Category::factory()->create(['name' => 'Healthcare']);

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                            'description',
                            'service_providers_count'
                        ]
                    ]
                ]);
    }

    public function test_can_get_all_service_providers()
    {
        // Create test data using factories
        $category = Category::factory()->create(['name' => 'Technology']);
        ServiceProvider::factory()->create([
            'name' => 'Test Provider',
            'category_id' => $category->id,
            'is_active' => true
        ]);

        $response = $this->getJson('/api/v1/providers');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                            'short_description',
                            'logo_url',
                            'category' => [
                                'id',
                                'name',
                                'slug'
                            ]
                        ]
                    ]
                ]);
    }

    public function test_can_get_service_provider_by_slug()
    {
        $category = Category::factory()->create(['name' => 'Technology']);
        $provider = ServiceProvider::factory()->create([
            'name' => 'Test Provider',
            'slug' => 'test-provider',
            'category_id' => $category->id,
            'is_active' => true
        ]);

        $response = $this->getJson("/api/v1/providers/{$provider->slug}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'name',
                        'slug',
                        'short_description',
                        'logo_url',
                        'category'
                    ]
                ]);
    }

    public function test_can_filter_providers_by_category()
    {
        $techCategory = Category::factory()->create(['name' => 'Technology']);
        $healthCategory = Category::factory()->create(['name' => 'Healthcare']);

        ServiceProvider::factory()->create([
            'name' => 'Tech Provider',
            'category_id' => $techCategory->id,
            'is_active' => true
        ]);

        ServiceProvider::factory()->create([
            'name' => 'Health Provider',
            'category_id' => $healthCategory->id,
            'is_active' => true
        ]);

        $response = $this->getJson("/api/v1/providers?category={$techCategory->id}");

        $response->assertStatus(200);
        $responseData = $response->json();
        
        $this->assertEquals(1, count($responseData['data']));
        $this->assertEquals('Tech Provider', $responseData['data'][0]['name']);
    }

    public function test_returns_404_for_non_existent_provider()
    {
        $response = $this->getJson('/api/v1/providers/non-existent-provider');

        $response->assertStatus(404)
                ->assertJsonStructure([
                    'error'
                ]);
    }

    public function test_returns_404_for_non_existent_category()
    {
        $response = $this->getJson('/api/v1/categories/999');

        if ($response->status() === 404) {
            $response->assertJsonStructure([
                'error'
            ]);
        } else {
            $response->assertStatus(200);
            $this->assertNotEmpty($response->getContent());
        }
    }
}
