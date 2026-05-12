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
        Schema::create('cookie_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->boolean('necessary')->default(true);
            $table->boolean('analytics')->default(false);
            $table->boolean('personalization')->default(false);
            $table->boolean('marketing')->default(false);
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('consented_at')->nullable();
            $table->timestamps();
        });

        Schema::create('gdpr_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('export_path')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('gdpr_deletions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('anonymized_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('new_followers')->default(true);
            $table->boolean('review_replies')->default(true);
            $table->boolean('recommendation_validation')->default(true);
            $table->boolean('tagged_in_review')->default(true);
            $table->boolean('hidden_gem_updates')->default(true);
            $table->boolean('local_status_updates')->default(true);
            $table->boolean('spot_updates')->default(true);
            $table->boolean('marketing')->default(false);
            $table->boolean('email_enabled')->default(true);
            $table->boolean('push_enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->string('collection')->nullable();
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->json('alt_text')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users');
            $table->string('moderation_status')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->nullable();
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('intro')->nullable();
            $table->json('content');
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
            $table->boolean('is_system_page')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
        Schema::dropIfExists('media');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('gdpr_deletions');
        Schema::dropIfExists('gdpr_exports');
        Schema::dropIfExists('cookie_consents');
    }
};
