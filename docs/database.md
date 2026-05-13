# Database Schema

## Core Tables

### spots
- `recommendation_score` (decimal): Aggregated score from recommendations.
- `review_score` (decimal): Aggregated score from weighted reviews.
- `hidden_gem_score` (decimal): Score indicating hidden gem status (0-100).
- `tourist_saturation_score` (decimal): Ratio of tourist vs local activity.
- `local_trust_score` (decimal): Strength of trust from verified locals.
- `community_match_score` (decimal): Relevance to specific communities.

### user_reputation
Stores granular reputation data for users.
- `user_id`, `region_id`, `sector_id`, `category_id`, `community_id`
- `local_status`: Calculated status in the region.
- `recommendation_count`
- `confirmed_recommendation_score`
- `review_score`
- `follower_count`
- `hidden_gem_score`
- `trust_score`: Main reputation metric (0-100+).
- `rank`: Global or regional rank.

## Interaction Tables

### recommendations
- `trust_score`: The calculated weight of this recommendation.
- `visibility_score`: How prominently this should be shown.

### reviews
- `weight`: The influence of this review on the spot's overall score.
- `visibility_score`: Display priority.

## Supporting Tables

### spot_community_profiles
- `spot_id`, `community_id`
- `percentage`: Distribution of this community's influence on the spot.
- `confidence_score`
