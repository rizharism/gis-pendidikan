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
        Schema::create('education_facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('klas', ['sd', 'smp', 'sma', 'universitas']);
            $table->text('address');
            $table->string('image')->nullable();
            $table->text('description');
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_facilities');
    }
};
