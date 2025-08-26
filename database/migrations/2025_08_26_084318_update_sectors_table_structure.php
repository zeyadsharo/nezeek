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
        Schema::table('sectors', function (Blueprint $table) {
            // Drop the kurdish_title column
            $table->dropColumn('kurdish_title');

            // Rename arabic_title to title
            $table->renameColumn('arabic_title', 'title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sectors', function (Blueprint $table) {
            // Revert the changes
            $table->renameColumn('title', 'arabic_title');
            $table->string('kurdish_title')->after('arabic_title');
        });
    }
};
