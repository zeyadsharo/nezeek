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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Basic Information
            $table->string('name')->unique(); // Property identifier
            $table->string('title'); // Arabic title
            $table->text('description')->nullable();

            // Property Configuration
            $table->enum('type', [
                'text',           // Single line text
                'textarea',       // Multi-line text
                'number',         // Numeric input
                'decimal',        // Decimal number
                'select',         // Single selection
                'multiselect',    // Multiple selection
                'checkbox',       // Boolean checkbox
                'radio',          // Radio buttons
                'date',           // Date picker
                'datetime',       // Date and time picker
                'time',           // Time picker
                'file',           // File upload
                'image',          // Image upload
                'url',            // URL input
                'email',          // Email input
                'phone',          // Phone number
                'color',          // Color picker
                'range'           // Range slider
            ]);

            // Values & Options
            $table->json('options')->nullable(); // For select, radio, etc.
            $table->json('default_value')->nullable();
            $table->string('unit')->nullable();
            $table->string('placeholder')->nullable();

            // Validation & Constraints
            $table->boolean('is_required')->default(false);
            $table->string('validation_rules')->nullable(); // Laravel validation rules
            $table->integer('min_length')->nullable();
            $table->integer('max_length')->nullable();
            $table->decimal('min_value', 15, 6)->nullable();
            $table->decimal('max_value', 15, 6)->nullable();
            $table->integer('min_selections')->nullable(); // For multiselect
            $table->integer('max_selections')->nullable(); // For multiselect

            // Display & UI
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_searchable')->default(false);
            $table->boolean('is_filterable')->default(false);
            $table->boolean('is_sortable')->default(false);

            // Advanced Features
            $table->boolean('is_unique')->default(false);
            $table->boolean('is_encrypted')->default(false);
            $table->string('group')->nullable(); // Group properties together
            $table->json('conditional_logic')->nullable(); // Show/hide based on other properties

            // Metadata
            $table->string('help_text')->nullable();

            // Indexes
            $table->index('type');
            $table->index('is_required');
            $table->index('is_searchable');
            $table->index('is_filterable');
            $table->index('group');
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
