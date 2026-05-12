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
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('followed_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('user_reputation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->nullable()->constrained();
            $table->foreignId('sector_id')->nullable()->constrained();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('community_id')->nullable()->constrained();
            $table->string('local_status')->nullable();
            $table->integer('recommendation_count')->default(0);
            $table->decimal('confirmed_recommendation_score', 8, 2)->nullable();
            $table->decimal('review_score', 8, 2)->nullable();
            $table->integer('follower_count')->default(0);
            $table->decimal('hidden_gem_score', 8, 2)->nullable();
            $table->decimal('trust_score', 8, 2)->nullable();
            $table->integer('rank')->nullable();
            $table->timestamps();
        });

        Schema::create('timeline_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type');
            $table->nullableMorphs('eventable');
            $table->json('payload')->nullable();
            $table->foreignId('region_id')->nullable()->constrained();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('spot_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            $table->timestamp('checked_in_at');
            $table->string('visit_source');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('verification_score', 5, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug')->unique();
            $table->json('description')->nullable();
            $table->string('source_type')->nullable();
            $table->string('source_name')->nullable();
            $table->string('source_url')->nullable();
            $table->foreignId('region_id')->nullable()->constrained();
            $table->foreignId('sector_id')->nullable()->constrained();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('default_community_id')->nullable()->constrained('communities');
            $table->json('landing_title')->nullable();
            $table->json('landing_intro')->nullable();
            $table->json('cta_text')->nullable();
            $table->json('success_message')->nullable();
            $table->json('publication_context')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('requires_login')->default(false);
            $table->boolean('requires_facebook_login')->default(false);
            $table->boolean('auto_create_spots')->default(false);
            $table->boolean('ai_enrichment_enabled')->default(true);
            $table->boolean('notify_spot_by_email')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('campaign_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('guest_token')->nullable();
            $table->string('submitted_name');
            $table->text('submitted_notes')->nullable();
            $table->string('submitted_place_hint')->nullable();
            $table->foreignId('matched_spot_id')->nullable()->constrained('spots');
            $table->foreignId('created_spot_id')->nullable()->constrained('spots');
            $table->json('ai_result')->nullable();
            $table->boolean('user_confirmed_spot')->default(false);
            $table->boolean('wants_to_recommend')->default(false);
            $table->boolean('consent_to_contact')->default(false);
            $table->boolean('consent_to_publish_quote')->default(false);
            $table->boolean('consent_to_terms')->default(false);
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::create('campaign_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('submission_id')->constrained('campaign_submissions')->onDelete('cascade');
            $table->foreignId('recommendation_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('spot_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('selected_for_publication')->default(false);
            $table->string('publication_status')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('spot_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('proof_document_path')->nullable();
            $table->text('proof_notes')->nullable();
            $table->string('status')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('claim_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_id')->nullable()->constrained()->onDelete('set null');
            $table->string('token')->unique();
            $table->string('email');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });

        Schema::create('spot_owner_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role');
            $table->timestamps();
        });

        Schema::create('content_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_user_id')->constrained('users')->onDelete('cascade');
            $table->morphs('reportable');
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->string('status');
            $table->foreignId('moderator_id')->nullable()->constrained('users');
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('moderation_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('moderator_id')->constrained('users')->onDelete('cascade');
            $table->morphs('target');
            $table->string('action');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moderation_actions');
        Schema::dropIfExists('content_reports');
        Schema::dropIfExists('spot_owner_roles');
        Schema::dropIfExists('claim_tokens');
        Schema::dropIfExists('spot_claims');
        Schema::dropIfExists('campaign_recommendations');
        Schema::dropIfExists('campaign_submissions');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('spot_visits');
        Schema::dropIfExists('timeline_events');
        Schema::dropIfExists('user_reputation');
        Schema::dropIfExists('follows');
    }
};
