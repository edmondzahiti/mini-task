<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_providers', function (Blueprint $table) {
            // Add indexes for better query performance
            $table->index(['is_active', 'category_id'], 'idx_active_category');
            $table->index('slug', 'idx_slug');
            $table->index(['is_active', 'name'], 'idx_active_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_providers', function (Blueprint $table) {
            $table->dropIndex('idx_active_category');
            $table->dropIndex('idx_slug');
            $table->dropIndex('idx_active_name');
        });
    }
};
