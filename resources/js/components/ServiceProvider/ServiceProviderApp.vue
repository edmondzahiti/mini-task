<template>
  <div id="service-provider-app">
    <!-- Header -->
    <header class="header">
      <div class="container">
        <div class="header-content">
          <h1 class="logo">Mini Task</h1>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main">
      <div class="container">
        <!-- Loading State -->
        <div v-if="loading" class="loading">
          <!-- Skeleton loading for better perceived performance -->
          <div class="skeleton-grid">
            <div v-for="n in 6" :key="n" class="skeleton-card">
              <div class="skeleton-logo"></div>
              <div class="skeleton-content">
                <div class="skeleton-title"></div>
                <div class="skeleton-category"></div>
                <div class="skeleton-description"></div>
                <div class="skeleton-button"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="error">
          <p>{{ error }}</p>
          <button @click="loadData" class="btn">Retry</button>
        </div>

        <!-- Service Provider List -->
        <div v-else-if="!selectedProvider">
          <!-- Filters -->
          <div class="filters">
            <label for="category-filter" class="filter-label">Filter by Category:</label>
            <select 
              id="category-filter"
              v-model="selectedCategory" 
              @change="filterByCategory" 
              class="filter-select"
              aria-label="Select a category to filter service providers"
            >
              <option value="">All Categories</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }} ({{ category.service_providers_count }})
              </option>
            </select>
          </div>

          <!-- Provider Grid -->
          <div v-if="filteredProviders.length > 0" class="provider-grid">
            <article v-for="provider in paginatedProviders" :key="provider.id" class="provider-card"
                     @click="selectProvider(provider)">
              <div v-if="provider.logo_path" class="provider-logo">
                <img
                    :src="getImageUrl(provider.logo_path)"
                    :alt="provider.name + ' logo'"
                    :loading="isAboveTheFold(provider) ? 'eager' : 'lazy'"
                    width="300"
                    height="200"
                    decoding="async"
                    @load="onImageLoad"
                    @error="onImageError"
                >
              </div>
              <div v-else class="provider-logo-placeholder">
                <span>{{ provider.name.substring(0, 2).toUpperCase() }}</span>
              </div>

              <div class="provider-content">
                <h2 class="provider-name">{{ provider.name }}</h2>

                <div class="provider-category">
                  <span @click.stop="filterByCategoryId(provider.category?.id)" style="cursor: pointer;">
                    {{ provider.category?.name || 'Uncategorized' }}
                  </span>
                </div>

                <p class="provider-description">
                  {{ truncateDescription(provider.short_description || provider.description || '', 120) }}
                </p>

                <button class="btn" @click.stop="selectProvider(provider)">
                  View Details
                </button>
              </div>
            </article>
          </div>

          <!-- No Results -->
          <div v-else class="card">
            <p>No service providers found in this category.</p>
            <button @click="clearFilters" class="btn">View All Providers</button>
          </div>

          <!-- Results Count -->
          <div v-if="filteredProviders.length > 0" style="margin-top: 2rem; text-align: center; color: #6b7280;">
            <p>Showing {{ ((currentPage - 1) * perPage) + 1 }}-{{ Math.min(currentPage * perPage, totalProviders) }} of
              {{ totalProviders }} service provider{{ totalProviders !== 1 ? 's' : '' }}</p>
          </div>

          <!-- Pagination Controls -->
          <div v-if="totalPages > 1" class="pagination-controls">
            <button @click="prevPage" :disabled="!hasPrevPage" class="btn pagination-btn"
                    :class="{ disabled: !hasPrevPage }">
              ← Previous
            </button>
            <div class="page-info">
              <span>Page {{ currentPage }} of {{ totalPages }}</span>
            </div>
            <button @click="nextPage" :disabled="!hasNextPage" class="btn pagination-btn"
                    :class="{ disabled: !hasNextPage }">
              Next →
            </button>
          </div>
        </div>

        <!-- Provider Detail View -->
        <div v-else class="provider-detail">
          <button @click="goBack" class="btn back-btn">← Back to List</button>

          <div class="card">
            <div class="provider-detail-header">
              <div v-if="selectedProvider.logo_path" class="provider-detail-logo">
                <img
                    :src="getImageUrl(selectedProvider.logo_path)"
                    :alt="selectedProvider.name + ' logo'"
                    loading="lazy"
                    width="200"
                    height="150"
                    decoding="async"
                    @load="onImageLoad"
                    @error="onImageError"
                >
              </div>
              <div v-else class="provider-detail-logo-placeholder">
                <span>{{ (selectedProvider.name || 'SP').substring(0, 2).toUpperCase() }}</span>
              </div>
              <div class="provider-detail-info">
                <h1>{{ selectedProvider.name || 'Service Provider' }}</h1>
                <p class="provider-category">
                  <span @click="filterByCategoryId(selectedProvider.category?.id)" style="cursor: pointer;">
                    {{ selectedProvider.category?.name || 'Uncategorized' }}
                  </span>
                </p>
                <p class="provider-short-description">{{ selectedProvider.short_description || selectedProvider.description || '' }}</p>
              </div>
            </div>

            <div v-if="selectedProvider.description" class="provider-description-full">
              <h2>About</h2>
              <p>{{ selectedProvider.description }}</p>
            </div>

            <div class="provider-contact">
              <h2>Contact Information</h2>
              <div class="contact-grid">
                <div v-if="selectedProvider.website_url" class="contact-item">
                  <strong>Website:</strong>
                  <a :href="selectedProvider.website_url" target="_blank" rel="noopener">{{
                      selectedProvider.website_url
                    }}</a>
                </div>
                <div v-if="selectedProvider.contact_email" class="contact-item">
                  <strong>Email:</strong>
                  <a :href="'mailto:' + selectedProvider.contact_email">{{ selectedProvider.contact_email }}</a>
                </div>
                <div v-if="selectedProvider.contact_phone" class="contact-item">
                  <strong>Phone:</strong>
                  <a :href="'tel:' + selectedProvider.contact_phone">{{ selectedProvider.contact_phone }}</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ServiceProviderApp',
  data() {
    return {
      providers: [],
      categories: [],
      selectedCategory: '',
      selectedProvider: null,
      loading: true,
      error: null,
      currentPage: 1,
      perPage: 10,
      totalProviders: 0,
      performanceObserver: null
    }
  },
  computed: {
    filteredProviders() {
      let filtered = this.providers;
      if (this.selectedCategory) {
        filtered = this.providers.filter(provider => provider.category_id == this.selectedCategory);
      }
      this.totalProviders = filtered.length;
      return filtered;
    },
    paginatedProviders() {
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      return this.filteredProviders.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.totalProviders / this.perPage);
    },
    hasNextPage() {
      return this.currentPage < this.totalPages;
    },
    hasPrevPage() {
      return this.currentPage > 1;
    }
  },
  async mounted() {
    // Performance monitoring for LCP
    this.startPerformanceMonitoring();

    // Check for initial data from server for faster TTFB
    if (window.__INITIAL_DATA__) {
      this.categories = window.__INITIAL_DATA__.categories;
      this.providers = window.__INITIAL_DATA__.providers;
      this.handleInitialRoute();
      this.updateURL();
      this.loading = false;
      
      // Preload critical images in background
      this.$nextTick(() => {
        this.preloadCriticalImages();
      });
    } else {
      // Try to load from cache first, then API
      await this.loadDataWithCache();
    }
  },

  beforeUnmount() {
    // Clean up performance monitoring
    if (this.performanceObserver) {
      this.performanceObserver.disconnect();
    }
  },
  methods: {
    async loadData() {
      try {
        this.loading = true;
        this.error = null;

        // Check if we have cached data
        const cachedData = sessionStorage.getItem('serviceProviderData');
        if (cachedData) {
          const data = JSON.parse(cachedData);
          this.categories = data.categories;
          this.providers = data.providers;
          this.handleInitialRoute();
          this.updateURL();
          this.loading = false;
          return;
        }

        // Load categories and providers in parallel
        const [categoriesResponse, providersResponse] = await Promise.all([
          axios.get('/api/v1/categories'),
          axios.get('/api/v1/providers')
        ]);

        this.categories = categoriesResponse.data.data;
        this.providers = providersResponse.data.data;

        // Cache the data for faster subsequent loads
        sessionStorage.setItem('serviceProviderData', JSON.stringify({
          categories: this.categories,
          providers: this.providers
        }));

        // Handle initial route if someone visits a direct URL
        this.handleInitialRoute();

        // Update URL without page reload
        this.updateURL();

        // Preload critical images for better performance
        this.$nextTick(() => {
          this.preloadCriticalImages();
        });
      } catch (error) {
        console.error('Error loading data:', error);
        this.error = 'Failed to load data. Please try again.';
      } finally {
        this.loading = false;
      }
    },

    async filterByCategory() {
      this.selectedProvider = null;
      this.updateURL();
    },

    filterByCategoryId(categoryId) {
      this.selectedCategory = categoryId;
      this.selectedProvider = null;
      this.updateURL();
    },

    selectProvider(provider) {
      this.selectedProvider = provider;
      this.updateURL();
    },

    goBack() {
      this.selectedProvider = null;
      this.updateURL();
    },

    goHome() {
      this.selectedCategory = '';
      this.selectedProvider = null;
      this.updateURL();
    },

    clearFilters() {
      this.selectedCategory = '';
      this.updateURL();
    },

    handleInitialRoute() {
      const path = window.location.pathname;
      const searchParams = new URLSearchParams(window.location.search);

      // Handle provider detail route: /providers/{slug}
      if (path.startsWith('/providers/')) {
        const slug = path.split('/providers/')[1];
        const provider = this.providers.find(p => p.slug === slug);
        if (provider) {
          this.selectedProvider = provider;
        }
      }

      // Handle category filter: /providers?category=1
      const categoryId = searchParams.get('category');
      if (categoryId) {
        this.selectedCategory = categoryId;
      }
    },

    updateURL() {
      let url = '/providers';
      const params = new URLSearchParams();

      if (this.selectedCategory) {
        params.append('category', this.selectedCategory);
      }

      if (this.selectedProvider) {
        url = `/providers/${this.selectedProvider.slug}`;
      }

      if (params.toString()) {
        url += '?' + params.toString();
      }

      // Update URL without page reload
      window.history.pushState({}, '', url);
    },

    truncateDescription(text, length) {
      if (!text || typeof text !== 'string') return '';
      if (text.length <= length) return text;
      return text.substring(0, length) + '...';
    },

    // Performance monitoring for LCP optimization
    startPerformanceMonitoring() {
      if ('PerformanceObserver' in window) {
        this.performanceObserver = new PerformanceObserver((list) => {
          for (const entry of list.getEntries()) {
            if (entry.entryType === 'largest-contentful-paint') {
              console.log('LCP:', entry.startTime, 'ms');
            }
          }
        });
        this.performanceObserver.observe({entryTypes: ['largest-contentful-paint']});
      }
    },
    
    // Pagination methods
    prevPage() {
      if (this.hasPrevPage) {
        this.currentPage--;
        this.updateURL();
      }
    },

    nextPage() {
      if (this.hasNextPage) {
        this.currentPage++;
        this.updateURL();
      }
    },

    // Image loading optimization methods
    onImageLoad(event) {
      // Image loaded successfully - no animation needed
      console.log('Image loaded:', event.target.src);
    },

    onImageError(event) {
      // Handle image loading errors gracefully
      console.warn('Image failed to load:', event.target.src);
      // Don't hide the image, just log the error
      // The placeholder will show if the image fails to load
    },

    // Get responsive image srcset
    getResponsiveImageSrcset(originalUrl, format = 'webp') {
      if (!originalUrl) return '';
      
      // For now, just return the original URL since optimized versions don't exist
      return originalUrl;
    },

    // Get optimized image URL with WebP support
    getOptimizedImageUrl(originalUrl, format = 'webp') {
      if (!originalUrl) return '';
      
      // For now, just return the original URL since optimized versions don't exist
      return originalUrl;
    },

    // Check WebP support
    checkWebPSupport() {
      const canvas = document.createElement('canvas');
      canvas.width = 1;
      canvas.height = 1;
      return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
    },

    // Get proper image URL for storage
    getImageUrl(logoPath) {
      if (!logoPath) return '';
      
      // If it's already a full URL, return as is
      if (logoPath.startsWith('http')) {
        return logoPath;
      }
      
      // If it's a relative path, prepend storage URL
      if (logoPath.startsWith('/')) {
        return logoPath;
      }
      
      // For storage paths, prepend /storage/
      return `/storage/${logoPath}`;
    },

    // Check if provider is above the fold (first 3 items)
    isAboveTheFold(provider) {
      const index = this.paginatedProviders.findIndex(p => p.id === provider.id);
      return index < 3; // First 3 items should load eagerly
    },

    // Preload critical images
    preloadCriticalImages() {
      if (this.providers.length > 0) {
        // Preload first few images for better perceived performance
        const criticalImages = this.providers.slice(0, 3).map(provider => this.getImageUrl(provider.logo_path));
        
        // Prioritize first image for LCP
        if (criticalImages[0]) {
          const img = new Image();
          img.src = criticalImages[0];
        }
        
        // Preload others with link tags
        criticalImages.slice(1).forEach(url => {
          if (url) {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = url;
            document.head.appendChild(link);
          }
        });
      }
    },

    // Get cached data with expiry
    getCachedData() {
      try {
        const cachedData = sessionStorage.getItem('serviceProviderData');
        if (cachedData) {
          const data = JSON.parse(cachedData);
          const now = Date.now();
          const cacheExpiry = 5 * 60 * 1000; // 5 minutes
          
          if (data.timestamp && (now - data.timestamp) < cacheExpiry) {
            return data;
          }
        }
        return null;
      } catch (error) {
        return null;
      }
    },

    // Cache data with timestamp
    cacheData(categories, providers) {
      try {
        const data = {
          categories,
          providers,
          timestamp: Date.now()
        };
        sessionStorage.setItem('serviceProviderData', JSON.stringify(data));
      } catch (error) {
        console.warn('Failed to cache data:', error);
      }
    },

    // Load data with cache first strategy
    async loadDataWithCache() {
      try {
        this.loading = true;
        
        // Try to load from cache first
        const cachedData = this.getCachedData();
        if (cachedData) {
          this.categories = cachedData.categories;
          this.providers = cachedData.providers;
          this.handleInitialRoute();
          this.updateURL();
          this.loading = false;
          
          // Preload critical images after data loads
          this.$nextTick(() => {
            this.preloadCriticalImages();
          });
          return;
        }
        
        // Load from API if no cache
        const [categoriesResponse, providersResponse] = await Promise.all([
          axios.get('/api/v1/categories'),
          axios.get('/api/v1/providers')
        ]);
        
        this.categories = categoriesResponse.data;
        this.providers = providersResponse.data;
        
        // Cache the data
        this.cacheData(this.categories, this.providers);
        
        this.handleInitialRoute();
        this.updateURL();
        this.loading = false;
        
        // Preload critical images after data loads
        this.$nextTick(() => {
          this.preloadCriticalImages();
        });
      } catch (error) {
        console.error('Error loading data:', error);
        this.loading = false;
      }
    }
  }
}
</script>
