<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Change the gallery column from string (VARCHAR) to text so it can hold
     * longer JSON arrays of image paths.
     */
    public function up(): void
    {
        Schema::table('education_facilities', function (Blueprint $table) {
            $table->text('gallery')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('education_facilities', function (Blueprint $table) {
            $table->string('gallery')->nullable()->change();
        });
    }
};
