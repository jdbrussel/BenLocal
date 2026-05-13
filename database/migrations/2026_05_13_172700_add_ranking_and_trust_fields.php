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
        // 1. Update Spots table with ranking scores
        Schema::table('spots', function (Blueprint $table) {
            $table->decimal('recommendation_score', 8, 2)->default(0)->after('ai_enrichment_data');
            $table->decimal('review_score', 8, 2)->default(0)->after('recommendation_score');
            $table->decimal('hidden_gem_score', 8, 2)->default(0)->after('review_score');
            $table->decimal('tourist_saturation_score', 8, 2)->default(0)->after('hidden_gem_score');
            $table->decimal('local_trust_score', 8, 2)->default(0)->after('tourist_saturation_score');
            $table->decimal('community_match_score', 8, 2)->default(0)->after('local_trust_score');
        });

        // 2. Update Recommendations table with trust scores
        Schema::table('recommendations', function (Blueprint $table) {
            $table->decimal('trust_score', 8, 2)->default(0)->after('hidden_gem_candidate');
            $table->decimal('visibility_score', 8, 2)->default(0)->after('trust_score');
        });

        // 3. Update Reviews table with weight
        Schema::table('reviews', function (Blueprint $table) {
            $table->decimal('weight', 8, 2)->default(1.00)->after('visibility_score');
        });

        // 4. Create User Reputation History
        Schema::create('user_reputation_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->nullable()->constrained();
            $table->decimal('trust_score', 8, 2);
            $table->string('local_status');
            $table->json('metrics'); // Store full metrics snapshot
            $table->timestamp('created_at')->useCurrent();
        });

        // 5. Create Spot Score Snapshots
        Schema::create('spot_score_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            $table->decimal('recommendation_score', 8, 2);
            $table->decimal('review_score', 8, 2);
            $table->decimal('hidden_gem_score', 8, 2);
            $table->decimal('tourist_saturation_score', 8, 2);
            $table->decimal('local_trust_score', 8, 2);
            $table->timestamp('created_at')->useCurrent();
        });

        // 6. Create Hidden Gem History
        Schema::create('hidden_gem_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 8, 2);
            $table->boolean('is_gem');
            $table->timestamp('created_at')->useCurrent();
        });

        // 7. Create Recommendation Score Logs
        Schema::create('recommendation_score_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recommendation_id')->constrained()->onDelete('cascade');
            $table->decimal('trust_score', 8, 2);
            $table->decimal('visibility_score', 8, 2);
            $table->json('calculation_factors');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation_score_logs');
        Schema::dropIfExists('hidden_gem_history');
        Schema::dropIfExists('spot_score_snapshots');
        Schema::dropIfExists('user_reputation_history');

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('weight');
        });

        Schema::table('recommendations', function (Blueprint $table) {
            $table->dropColumn(['trust_score', 'visibility_score']);
        });

        Schema::table('spots', function (Blueprint $table) {
            $table->dropColumn([
                'recommendation_score',
                'review_score',
                'hidden_gem_score',
                'tourist_saturation_score',
                'local_trust_score',
                'community_match_score'
            ]);
        });
    }
};
