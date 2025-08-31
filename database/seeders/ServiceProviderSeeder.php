<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\ServiceProvider;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Sequence;

class ServiceProviderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $providersByCategory = [
            'Technology' => [
                'TechFlow Solutions',
                'CloudNet Systems',
                'DataViz Analytics',
                'Web Development Studio',
            ],
            'Healthcare' => [
                'HealthFirst Medical Center',
                'Wellness Solutions',
                'Medical Innovations',
            ],
            'Finance' => [
                'SecureBank Financial',
                'InsurePro Group',
                'Investment Partners',
                'Credit Solutions',
            ],
        ];

        foreach ($providersByCategory as $categoryName => $providerNames) {
            $categorySlug = Str::slug($categoryName);

            $sequence = new Sequence(
                ...collect($providerNames)->map(fn($name) => [
                'name' => $name,
                'slug' => Str::slug($name),
                'short_description' => $faker->sentence(8, true),
                'description' => collect($faker->paragraphs(3))->implode(' '),
                'website_url' => $faker->url(),
                'contact_email' => $faker->companyEmail(),
                'contact_phone' => $faker->phoneNumber(),
            ])->all()
            );

            Category::factory()
                ->state([
                    'name' => $categoryName,
                    'slug' => $categorySlug,
                    'description' => $faker->sentence(10, true),
                ])
                ->has(
                    ServiceProvider::factory()
                        ->count(count($providerNames))
                        ->state($sequence),
                    'serviceProviders'
                )
                ->create();
        }
    }
}
