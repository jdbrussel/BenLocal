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

## Seeder Order
The order in `DatabaseSeeder` is critical as activity seeders depend on existing Users, Spots, Recommendations, and Reviews.

## How to Test Feed Scenarios
1. Run `php artisan db:seed`.
2. Login as one of the demo users (e.g., `jan@benlocal.test`).
3. Visit the `/feed` page.
4. Verify that:
   - Followed users' activity appears prominently.
   - Community-relevant content is visible.
   - Region filtering (Tenerife) is applied.
