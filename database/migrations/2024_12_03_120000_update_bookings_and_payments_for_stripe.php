<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing booking statuses from 'pending' to 'pending_payment'
        DB::table('bookings')
            ->where('status', 'pending')
            ->update(['status' => 'pending_payment']);

        // Update payments table to use 'stripe' as default provider
        Schema::table('payments', function (Blueprint $table) {
            $table->string('provider')->default('stripe')->change();
        });
    }

    public function down(): void
    {
        // Revert booking statuses
        DB::table('bookings')
            ->where('status', 'pending_payment')
            ->update(['status' => 'pending']);

        // Revert payments provider default
        Schema::table('payments', function (Blueprint $table) {
            $table->string('provider')->default('paystack')->change();
        });
    }
};
