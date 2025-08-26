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
        Schema::table('category_properties', function (Blueprint $table) {
            // Add property group relationship
            $table->unsignedBigInteger('property_group_id')->nullable()->after('property_id');
            $table->foreign('property_group_id')->references('id')->on('property_groups')->nullOnDelete();

            // Add index for better performance
            $table->index('property_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_properties', function (Blueprint $table) {
            $table->dropForeign(['property_group_id']);
            $table->dropIndex(['property_group_id']);
            $table->dropColumn('property_group_id');
        });
    }
};
