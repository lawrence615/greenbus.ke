<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('short_description', 300)->nullable();
            $table->text('description')->nullable();
            $table->string('duration_text')->nullable();
            $table->string('meeting_point')->nullable();
            $table->string('starts_at_time')->nullable();
            $table->boolean('is_daily')->default(true);
            $table->boolean('featured')->default(false);
            $table->unsignedInteger('base_price_adult');
            $table->unsignedInteger('base_price_child')->nullable();
            $table->unsignedInteger('base_price_infant')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
