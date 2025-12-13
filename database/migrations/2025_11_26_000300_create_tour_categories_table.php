<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('duration_type'); // hourly, half_day, full_day, multiple_days
            $table->timestamps();
        });

        Schema::table('tours', function (Blueprint $table) {
            $table->foreignId('tour_category_id')
                ->nullable()
                ->after('city_id')
                ->constrained('tour_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tour_category_id');
        });

        Schema::dropIfExists('tour_categories');
    }
};
