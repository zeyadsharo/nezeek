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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('arabic_title');
            $table->string('kurdish_title');
            $table->boolean('is_required')->default(false);
            $table->enum('type', ['textbox','number','select','checkbox','date']);
            $table->text('values')->nullable();
            $table->string('unit')->nullable();
            $table->string('icon')->nullable();
            $table->string('validation_rule')->nullable();
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
