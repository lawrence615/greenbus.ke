<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->default('flutterwave');
            $table->string('provider_reference')->nullable();
            $table->string('provider_transaction_id')->nullable();
            $table->unsignedInteger('amount');
            $table->decimal('amount_charged', 10, 2)->nullable();
            $table->decimal('amount_settled', 10, 2)->nullable();
            $table->decimal('provider_fee', 10, 2)->nullable();
            $table->string('payment_method')->default('card');
            $table->string('currency', 10)->default('KES');
            $table->string('status')->default('initiated');
            $table->json('raw_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
