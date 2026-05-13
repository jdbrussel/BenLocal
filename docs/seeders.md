# BenLocal Seeders

This document describes the seeding strategy and available seeders for BenLocal, particularly for Phase 8 (Timeline, Social Feed & Activity System).

## Core Seeders

| Seeder | Description |
|--------|-------------|
| `CommunitySeeder` | Creates the basic communities (NL, BE, ES, DE, UK, etc.). |
| `LocationSeeder` | Seeds Regions, Areas, and Places (Tenerife focused). |
| `UserSeeder` | Creates core demo users (Jan, Carlos, Sofie, Emma, Markus). |
| `SpotSeeder` | Seeds initial business locations. |
| `BusinessOwnerSeeder` | Creates realistic owner accounts for demo. |
| `SpotClaimDemoSeeder` | Seeds various claim scenarios (Pending, Approved, Rejected). |
| `ClaimTokenDemoSeeder` | Seeds token edge cases (Expired, Used, Campaign-linked). |
| `SpotOwnerRoleSeeder` | Assigns specific roles (Owner, Manager, Editor) to users. |
| `OwnerReviewResponseSeeder` | Seeds owner responses to reviews in multiple languages. |

## Phase 8: Feed & Activity Seeders

To make the feed feel alive, several specific seeders are used:

### 1. TimelineEventSeeder
Generates a large volume (500-1000) of random timeline events using the `TimelineEventFactory`. This ensures the global feed has historical data.

### 2. FollowActivitySeeder
Seeds social connections and corresponding `user_followed` events. Ensures that personalization based on follows can be tested.

### 3. RecommendationActivitySeeder
Converts existing recommendations into `recommendation_created` timeline events. Also generates `campaign_recommendation_created` events.

### 4. ReviewActivitySeeder
Converts reviews and reactions into `review_created` and `review_reaction_created` events. Also simulates `user_tagged_in_review` events.

### 5. HiddenGemActivitySeeder
Generates `hidden_gem_detected` and `spot_status_changed` events for relevant spots (Guachinches, Bodegas). Also seeds `spot_saved` activity.

### 6. CampaignActivitySeeder
Generates `campaign_submission_created` events and simulates business claim activity.

### 7. FeedDemoSeeder
Orchestrates specific scenarios for demo users to ensure their personalized feeds are rich:
- **Jan**: High activity, tagged in reviews, followed by many.
- **Sofie**: Focus on Belgian community and specific spot types.
- **Carlos**: Focus on Canarian hidden gems and guachinches.
- **Emma**: Tourist behavior, follows locals, saves many spots.
- **Markus**: Follows Jan, reviews quality/value spots.

## Phase 13: Check-ins & Verified Visits Seeders

To test the visit verification system, several seeders generate realistic data:

### 1. SpotVisitDemoSeeder
Generates a large volume (300-600) of spot visits across all sources (gps, qr, manual, reservation, owner_confirmation) with corresponding verification scores.

### 2. VerifiedVisitReviewSeeder
Links specific reviews to verified visits for core demo users (Jan, Carlos, Emma, Markus). It demonstrates how verified visits boost review credibility.

### 3. SuspiciousVisitSeeder
Seeds visits with far-away GPS coordinates to test distance validation and suspicious activity flags in Filament and API.

### 4. QrVisitDemoSeeder
Focuses on QR code verification, seeding valid, expired, and revoked token scenarios.

### 5. ManualVisitDemoSeeder
Seeds manually logged visits which typically have lower verification scores.

## Phase 15: GDPR, Privacy & Account Management Seeders

To test GDPR compliance and privacy flows:

### 1. GdprSeeder (Orchestrator)
Calls the following specific seeders in order:
- `UserPrivacyPreferenceSeeder`: Seeds profile visibility (`public`, `private`, `friends`) and content visibility flags.
- `ConsentHistorySeeder`: Seeds `CookieConsent` and `PrivacyAuditLog` entries for guests and users.
- `GdprExportSeeder`: Seeds `pending`, `processing`, `completed`, and `failed` data export requests.
- `GdprDeletionSeeder`: Seeds account deletion lifecycle requests.
- `AnonymizedUserSeeder`: Creates "Deleted User" scenarios with preserved anonymized reviews and recommendations.

## Seeder Order
The order in `DatabaseSeeder` is critical as activity and visit seeders depend on existing Users, Spots, Recommendations, and Reviews.

## How to Test Feed Scenarios
1. Run `php artisan db:seed`.
2. Login as one of the demo users (e.g., `jan@benlocal.test`).
3. Visit the `/feed` page.
4. Verify that:
   - Followed users' activity appears prominently.
   - Community-relevant content is visible.
   - Region filtering (Tenerife) is applied.

## Benchmark Seeders (Phase 17)

For performance testing, discovery filtering, and map marker clustering, we provide a set of benchmark seeders. These are **not** run by default and must be explicitly enabled.

### Configuration

Enable benchmark seeding in your `.env`:

```env
BENLOCAL_SEED_BENCHMARKS=true
BENLOCAL_BENCHMARK_USERS=5000
BENLOCAL_BENCHMARK_SPOTS=1000
BENLOCAL_BENCHMARK_REVIEWS=20000
BENLOCAL_BENCHMARK_RECOMMENDATIONS=5000
BENLOCAL_BENCHMARK_REACTIONS=50000
BENLOCAL_BENCHMARK_TIMELINE_EVENTS=100000
```

### Components

*   **PerformanceBenchmarkSeeder**: The master seeder that coordinates the process.
*   **LargeSpotDatasetSeeder**: Generates spots clustered in Tenerife (Costa Adeje, Los Cristianos, etc.).
*   **LargeReviewDatasetSeeder**: Generates large-scale review data with weighted distribution (popular vs. quiet spots).
*   **LargeTimelineDatasetSeeder**: Populates the timeline with diverse events.
*   **QueueJobDemoSeeder**: Prepares records (spots, users) that appear "stale" to trigger background processing jobs.
*   **CacheScenarioSeeder**: Creates specific records to verify cache invalidation and regional caching.

### How to Run

```bash
# Run with full database refresh
php artisan migrate:fresh --seed

# Run only benchmark seeder (if base data already exists)
php artisan db:seed --class=PerformanceBenchmarkSeeder
```
