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
        Schema::create('appointments', function (Blueprint $table) {
           $table->id();
            $table->string('private_label')->nullable();
            $table->string('public_label')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->date('auto_delete_at')->nullable();
            $table->string('color')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->boolean('is_private');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
