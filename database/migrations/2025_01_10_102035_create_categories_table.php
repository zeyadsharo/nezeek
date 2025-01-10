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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('sector_id');
            $table->string('arabic_title');
            $table->string('kurdish_title');
            $table->integer('display_order');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('icon')->nullable();
            $table->foreign('sector_id')->references('id')->on('sectors')->cascadeOnDelete();
            $table->index('sector_id');
            $table->foreign('parent_id')->references('id')->on('categories')->cascadeOnDelete();
           // $table->unique(['sector_id', 'display_order']);
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
