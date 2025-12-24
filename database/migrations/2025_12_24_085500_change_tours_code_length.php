<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropUnique('tours_code_unique');
        });

        DB::statement('ALTER TABLE tours MODIFY code VARCHAR(255) NULL');

        Schema::table('tours', function (Blueprint $table) {
            $table->unique('code');
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropUnique('tours_code_unique');
        });

        DB::statement('ALTER TABLE tours MODIFY code VARCHAR(10) NULL');

        Schema::table('tours', function (Blueprint $table) {
            $table->unique('code');
        });
    }
};
