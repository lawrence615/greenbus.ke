<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->date('date');
            $table->string('time')->nullable();
            $table->unsignedInteger('adults');
            $table->unsignedInteger('children')->default(0);
            $table->unsignedInteger('infants')->default(0);
            $table->unsignedInteger('total_amount');
            $table->string('currency', 10)->default('KES');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('pickup_location')->nullable();
            $table->text('special_requests')->nullable();
            $table->string('status')->default('pending_payment');
            $table->timestamp('refunded_at')->nullable();
            $table->text('refund_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
