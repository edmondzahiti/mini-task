<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryApiController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        try {
            $categories = $this->service->getAllWithActiveProvidersCount();
            
            return response()->json(['data' => CategoryResource::collection($categories)]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load categories'], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $category = $this->service->getById($id);
            
            if (!$category) {
                return response()->json(['error' => 'Category not found'], 404);
            }
            
            return response()->json(['data' => new CategoryResource($category)]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load category'], 500);
        }
    }
}
