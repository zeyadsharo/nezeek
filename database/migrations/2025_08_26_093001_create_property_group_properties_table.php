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
        Schema::create('property_group_properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_group_id');
            $table->unsignedBigInteger('property_id');
            $table->string('custom_label')->nullable();
            $table->text('custom_help_text')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_editable')->default(true);
            $table->boolean('is_required')->default(false);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('property_group_id')->references('id')->on('property_groups')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');

            // Unique constraint
            $table->unique(['property_group_id', 'property_id']);

            // Indexes
            $table->index('property_group_id');
            $table->index('property_id');
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_group_properties');
    }
};
