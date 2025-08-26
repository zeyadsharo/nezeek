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
        Schema::create('property_groups', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Basic Information
            $table->string('name')->unique();
            $table->string('title'); // Arabic title
            $table->text('description')->nullable();

            // Display & Organization
            $table->integer('display_order')->default(0);
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_collapsible')->default(true);
            $table->boolean('is_expanded_by_default')->default(false);

            // Status
            $table->boolean('is_active')->default(true);

            // Constraints & Indexes
            $table->index('display_order');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_groups');
    }
};
