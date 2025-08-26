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
        Schema::create('property_values', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Relationships
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('item_id'); // The item this value belongs to
            $table->string('item_type'); // The model type (e.g., 'App\Models\Product')

            // Value storage
            $table->text('value')->nullable(); // For simple values
            $table->json('json_value')->nullable(); // For complex values (arrays, objects)
            $table->decimal('numeric_value', 15, 6)->nullable(); // For numeric values
            $table->boolean('boolean_value')->nullable(); // For boolean values
            $table->date('date_value')->nullable(); // For date values
            $table->datetime('datetime_value')->nullable(); // For datetime values

            // Metadata
            $table->string('unit')->nullable(); // Unit of measurement
            $table->text('notes')->nullable(); // Additional notes
            $table->string('source')->nullable(); // Source of this value
            $table->boolean('is_verified')->default(false); // Whether this value has been verified

            // Constraints & Indexes
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->foreign('property_id')->references('id')->on('properties')->cascadeOnDelete();

            $table->index(['category_id', 'property_id']);
            $table->index(['item_id', 'item_type']);
            $table->index(['property_id', 'value']);
            $table->index('is_verified');

            // Ensure unique combination
            $table->unique(['category_id', 'property_id', 'item_id', 'item_type'], 'unique_property_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_values');
    }
};
