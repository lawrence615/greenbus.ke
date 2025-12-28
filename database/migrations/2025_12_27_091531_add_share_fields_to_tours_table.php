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
        Schema::table('tours', function (Blueprint $table) {
            $table->string('share_token')->nullable()->unique()->after('status');
            $table->enum('share_status', ['draft', 'ready', 'shared', 'expired'])->default('draft')->after('share_token');
            $table->timestamp('shared_at')->nullable()->after('share_status');
            $table->timestamp('expires_at')->nullable()->after('shared_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['share_token', 'share_status', 'shared_at', 'expires_at']);
        });
    }
};
