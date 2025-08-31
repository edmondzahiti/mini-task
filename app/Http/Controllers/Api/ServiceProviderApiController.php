<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceProviderResource;
use App\Services\ServiceProviderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ServiceProviderApiController extends Controller
{
    protected $service;

    public function __construct(ServiceProviderService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $categoryId = $request->get('category');
            $providers = $this->service->getAllWithCaching($categoryId);
            
            return response()->json(['data' => ServiceProviderResource::collection($providers)]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load service providers'], 500);
        }
    }

    public function show(string $slug): JsonResponse
    {
        try {
            $provider = $this->service->getBySlugWithCaching($slug);
            
            if (!$provider) {
                return response()->json(['error' => 'Service provider not found'], 404);
            }
            
            return response()->json(['data' => new ServiceProviderResource($provider)]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load service provider'], 500);
        }
    }
}
