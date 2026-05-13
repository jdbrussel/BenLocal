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
        Schema::table('spots', function (Blueprint $table) {
            $table->timestamp('translated_at')->nullable()->after('original_language');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->timestamp('translated_at')->nullable()->after('original_language');
        });

        Schema::table('recommendations', function (Blueprint $table) {
            $table->timestamp('translated_at')->nullable()->after('original_language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spots', function (Blueprint $table) {
            $table->dropColumn('translated_at');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('translated_at');
        });

        Schema::table('recommendations', function (Blueprint $table) {
            $table->dropColumn('translated_at');
        });
    }
};
