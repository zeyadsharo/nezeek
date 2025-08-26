<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Basic Information
            $table->string('name')->unique(); // Slug/identifier
            $table->string('title'); // Arabic title
            $table->text('description')->nullable();

            // Relationships
            $table->unsignedBigInteger('sector_id');
            $table->unsignedBigInteger('parent_id')->nullable();

            // Display & Organization
            $table->integer('display_order')->default(0);
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('color')->nullable();

            // Status & Metadata
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // SEO
            $table->string('slug')->unique();

            // Constraints & Indexes
            $table->foreign('sector_id')->references('id')->on('sectors')->cascadeOnDelete();
            $table->foreign('parent_id')->references('id')->on('categories')->cascadeOnDelete();

            $table->index(['sector_id', 'display_order']);
            $table->index(['parent_id', 'display_order']);
            $table->index('is_active');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
