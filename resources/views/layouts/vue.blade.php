<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Mini Task - Service Provider Directory</title>
    <meta name="description" content="Find the best service providers in your area. Filter by category to find exactly what you need.">
    
    <!-- Prevent favicon requests -->
    <link rel="icon" href="data:,">

    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('build/assets/js/app-LyGx5hCp.js') }}" as="script">
    <link rel="preload" href="{{ asset('build/assets/css/app-Dv57dB3o.css') }}" as="style">
    <link rel="preload" href="/storage/logos/test-image.jpg" as="image">
    
    <!-- Critical CSS inline for faster rendering - OPTIMIZED -->
    <style>
        *{margin:0;padding:0;box-sizing:border-box}body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;line-height:1.6;color:#1f2937;background:#f9fafb}.container{max-width:1200px;margin:0 auto;padding:0 1rem}.header{background:#fff;box-shadow:0 2px 4px rgba(0,0,0,.1);padding:1rem 0;position:sticky;top:0;z-index:100}.header h1{color:#1e40af;font-size:1.5rem;font-weight:600}.main{padding:2rem 0}.card{background:#fff;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,.1);padding:1.5rem;margin-bottom:1rem}.btn{display:inline-block;padding:.75rem 1.5rem;background:#1e40af;color:#ffffff;text-decoration:none;border-radius:6px;border:none;cursor:pointer;transition:background .2s;font-weight:500;font-size:1rem}.btn:hover{background:#1e3a8a}.btn:disabled{background:#9ca3af;cursor:not-allowed}.filters{margin-bottom:2rem}.filter-label{display:block;margin-bottom:.5rem;font-weight:500;color:#374151}.filter-select{padding:.75rem;border:2px solid #d1d5db;border-radius:6px;background:white;min-width:250px;font-size:1rem;color:#374151}.filter-select:focus{outline:none;border-color:#1e40af;box-shadow:0 0 0 3px rgba(30,64,175,.1)}.provider-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem}.provider-card{background:#fff;border-radius:12px;box-shadow:0 4px 6px rgba(0,0,0,.1);overflow:hidden;transition:transform .2s;cursor:pointer}.provider-card:hover{transform:translateY(-2px);box-shadow:0 8px 15px rgba(0,0,0,.15)}.provider-logo{width:100%;height:200px;object-fit:cover;background:#f3f4f6;transition:opacity .3s}.provider-content{padding:1.5rem}.provider-name{font-size:1.25rem;font-weight:600;margin-bottom:.5rem;color:#111827}.provider-category{color:#4b5563;font-size:.875rem;margin-bottom:1rem}.provider-category span{background:#e5e7eb;color:#374151;padding:.25rem .75rem;border-radius:9999px;font-weight:500}.provider-description{color:#374151;line-height:1.6}.loading{text-align:center;padding:2rem;color:#6b7280}.loading-spinner{display:inline-block;width:20px;height:20px;border:2px solid #e5e7eb;border-top:2px solid #1e40af;border-radius:50%;animation:spin 1s linear infinite}@keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}@media (max-width:768px){.provider-grid{grid-template-columns:1fr}.container{padding:0 .5rem}}
    </style>
    
    <!-- Preload critical data for faster TTFB -->
    @if(isset($categories) && isset($providers))
    <script>
        window.__INITIAL_DATA__ = {
            categories: @json($categories),
            providers: @json($providers)
        };
    </script>
    @endif

    <!-- Optimized JavaScript loading -->
    <script type="module" src="{{ asset('build/assets/js/app-LyGx5hCp.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('build/assets/css/app-Dv57dB3o.css') }}">
    

</head>
<body>
    <!-- Vue.js App Mount Point -->
    <div id="app">
        <!-- Optimized loading placeholder -->
        <header class="header">
            <div class="container">
                <h1>Mini Task</h1>
            </div>
        </header>
        <main class="main">
            <div class="container">
                <div class="loading">
                    <div class="loading-spinner"></div>
                    <p>Loading...</p>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Fallback for CSS if JavaScript is disabled -->
    <noscript>
        <link rel="stylesheet" href="{{ asset('build/assets/css/app-Dv57dB3o.css') }}">
    </noscript>
</body>
</html>
