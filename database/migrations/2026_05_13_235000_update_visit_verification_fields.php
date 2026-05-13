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
            $table->string('qr_token')->nullable()->unique()->after('verified_at');
        });

        Schema::table('spot_visits', function (Blueprint $table) {
            $table->boolean('is_suspicious')->default(false)->after('verification_score');
            $table->json('metadata')->nullable()->after('is_suspicious');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spot_visits', function (Blueprint $table) {
            $table->dropColumn(['is_suspicious', 'metadata']);
        });

        Schema::table('spots', function (Blueprint $table) {
            $table->dropColumn('qr_token');
        });
    }
};
