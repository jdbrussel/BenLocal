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
        Schema::create('spot_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            $table->string('metric_type'); // 'view', 'click_website', 'click_phone', 'click_direction'
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('guest_token')->nullable();
            $table->string('source')->nullable(); // 'map', 'search', 'feed', 'campaign'
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spot_analytics');
    }
};
