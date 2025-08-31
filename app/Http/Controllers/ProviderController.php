<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProviderController extends Controller
{
    public function index()
    {
        // Cache the data for 5 minutes
        $data = Cache::remember('providers_data', 300, function () {
            $categories = Category::select('id', 'name', 'description')
                ->withCount('serviceProviders')
                ->orderBy('name')
                ->get();
                
            $providers = ServiceProvider::select('id', 'name', 'description', 'logo_path', 'category_id')
                ->with(['category:id,name'])
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
                
            return [
                'categories' => $categories,
                'providers' => $providers
            ];
        });
        
        return view('vue-app', $data);
    }
}
