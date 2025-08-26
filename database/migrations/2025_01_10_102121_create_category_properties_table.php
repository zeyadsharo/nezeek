<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the database seeds.
     */
    public function up(): void
    {
        Schema::create('category_properties', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Relationships
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('property_id');

            // Display & Organization
            $table->integer('display_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_editable')->default(true);
            $table->boolean('is_required')->default(false); // Override property default

            // Customization per category
            $table->string('custom_label')->nullable(); // Override property title for this category
            $table->text('custom_help_text')->nullable();

            // Validation overrides
            $table->string('custom_validation_rules')->nullable();
            $table->json('custom_options')->nullable(); // Override property options for this category

            // Conditional display
            $table->json('show_when')->nullable(); // Show this property when other properties have specific values
            $table->json('hide_when')->nullable(); // Hide this property when other properties have specific values

            // Constraints & Indexes
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->foreign('property_id')->references('id')->on('properties')->cascadeOnDelete();

            $table->index(['category_id', 'display_order']);
            $table->index(['property_id', 'display_order']);
            $table->index('is_visible');
            $table->index('is_editable');
            $table->index('is_required');

            // Ensure unique combination
            $table->unique(['category_id', 'property_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_properties');
    }
};
