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
        Schema::create('spots', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug')->unique();
            $table->json('description')->nullable();
            $table->string('original_language')->nullable();
            $table->foreignId('sector_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('region_id')->constrained();
            $table->foreignId('area_id')->nullable()->constrained();
            $table->foreignId('place_id')->nullable()->constrained();
            $table->json('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->json('opening_hours')->nullable();
            $table->integer('price_level')->nullable();
            $table->json('spec_values')->nullable();
            $table->string('source')->nullable();
            $table->string('source_reference')->nullable();
            $table->string('lifecycle_status');
            $table->boolean('is_claimed')->default(false);
            $table->timestamp('claimed_at')->nullable();
            $table->boolean('verified_business')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->boolean('ai_enriched')->default(false);
            $table->json('ai_enrichment_data')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('spot_community_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            $table->foreignId('community_id')->constrained()->onDelete('cascade');
            $table->decimal('percentage', 5, 2)->nullable();
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });

        Schema::create('spot_badges', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('name');
            $table->json('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });

        Schema::create('spot_badge_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            $table->foreignId('badge_id')->constrained('spot_badges')->onDelete('cascade');
            $table->foreignId('assigned_by')->nullable()->constrained('users');
            $table->boolean('auto_assigned')->default(false);
            $table->timestamps();
        });

        Schema::create('user_region_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->boolean('claimed_by_user')->default(false);
            $table->boolean('residence_based')->default(false);
            $table->boolean('ip_supported')->default(false);
            $table->boolean('manually_verified')->default(false);
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained();
            $table->foreignId('community_id')->nullable()->constrained();
            $table->json('title')->nullable();
            $table->json('motivation')->nullable();
            $table->string('original_language')->nullable();
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->boolean('hidden_gem_candidate')->default(false);
            $table->string('moderation_status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            $table->foreignId('recommendation_id')->nullable()->constrained();
            $table->unsignedBigInteger('spot_visit_id')->nullable(); // Will constrain in next migration if needed or leave as is
            $table->decimal('overall_rating', 3, 2)->nullable();
            $table->json('rating_values')->nullable();
            $table->json('review_text')->nullable();
            $table->string('original_language')->nullable();
            $table->timestamp('visited_at')->nullable();
            $table->string('user_region_status_at_time')->nullable();
            $table->foreignId('user_community_id')->nullable()->constrained('communities');
            $table->boolean('confirms_recommendation')->nullable();
            $table->json('perceived_community_profile')->nullable();
            $table->decimal('visibility_score', 5, 2)->nullable();
            $table->string('moderation_status')->nullable();
            $table->integer('flagged_count')->default(0);
            $table->boolean('verified_visit')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('review_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            $table->string('path');
            $table->json('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('review_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            $table->string('reaction');
            $table->decimal('weight', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_reactions');
        Schema::dropIfExists('review_photos');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('user_region_statuses');
        Schema::dropIfExists('spot_badge_assignments');
        Schema::dropIfExists('spot_badges');
        Schema::dropIfExists('spot_community_profiles');
        Schema::dropIfExists('spots');
    }
};
