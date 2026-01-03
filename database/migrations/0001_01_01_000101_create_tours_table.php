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
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tour_category_id')->constrained()->cascadeOnDelete();
            $table->enum('tour_type', ['standard', 'bespoke', 'other'])->default('standard');
            $table->string('code')->unique()->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->longText('included')->nullable();
            $table->longText('excluded')->nullable();
            $table->longText('additional_information')->nullable();
            $table->longText('cancellation_policy')->nullable();
            $table->string('duration_text')->nullable();
            $table->integer('no_of_people')->nullable();
            $table->string('meeting_point')->nullable();
            $table->string('starts_at_time')->nullable();
            $table->unsignedInteger('cut_off_time')->default(15);
            $table->boolean('is_daily')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_the_bus_tour')->default(false);
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
