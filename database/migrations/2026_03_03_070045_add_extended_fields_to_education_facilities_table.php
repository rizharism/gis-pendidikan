<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('education_facilities', function (Blueprint $table) {
            $table->string('school_code')->nullable()->unique()->after('description');
            $table->enum('accreditation', ['A', 'B', 'C', 'D'])->nullable()->after('school_code');
            $table->string('principal_name')->nullable()->after('accreditation');
            $table->string('phone')->nullable()->after('principal_name');
            $table->string('email')->nullable()->after('phone');
            $table->string('website')->nullable()->after('email');
            $table->integer('student_capacity')->nullable()->after('website');
            $table->integer('teacher_count')->nullable()->after('student_capacity');
            $table->json('opening_hours')->nullable()->after('teacher_count');
            $table->string('video_url')->nullable()->after('opening_hours');
        });

        // Migrate existing image data into gallery JSON format
        DB::table('education_facilities')->get()->each(function ($row) {
            $gallery = $row->image ? json_encode([$row->image]) : null;
            DB::table('education_facilities')
                ->where('id', $row->id)
                ->update(['image' => $gallery]);
        });

        // Rename image column to gallery
        Schema::table('education_facilities', function (Blueprint $table) {
            $table->renameColumn('image', 'gallery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename gallery back to image
        Schema::table('education_facilities', function (Blueprint $table) {
            $table->renameColumn('gallery', 'image');
        });

        // Convert gallery JSON back to single image string
        DB::table('education_facilities')->get()->each(function ($row) {
            $images = json_decode($row->image, true);
            $singleImage = is_array($images) && count($images) > 0 ? $images[0] : null;
            DB::table('education_facilities')
                ->where('id', $row->id)
                ->update(['image' => $singleImage]);
        });

        Schema::table('education_facilities', function (Blueprint $table) {
            $table->dropColumn([
                'school_code',
                'accreditation',
                'principal_name',
                'phone',
                'email',
                'website',
                'student_capacity',
                'teacher_count',
                'opening_hours',
                'video_url',
            ]);
        });
    }
};
