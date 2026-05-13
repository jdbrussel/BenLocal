# Services

## Trust & Ranking Services

### UserReputationService
Calculates and updates user reputation across different dimensions (Region, Sector, Category, Community).
- **Key Logic:** Factors in confirmed recommendations, review quality, hidden gems discovered, and verified visits.

### TrustGraphService
Manages personal trust relationships between users.
- **Key Logic:** Calculates weights based on follows and shared community context.

### ReviewWeightService
Determines the influence of a review on a spot's overall metrics.
- **Key Logic:** Rewards reviews from high-reputation users and verified visits.

### RecommendationScoreService
Calculates trust and visibility scores for individual recommendations.
- **Key Logic:** Uses user reputation and local status as primary multipliers.

### HiddenGemService
Identifies spots that qualify as "Hidden Gems".
- **Key Logic:** High local trust + Low total volume = High Hidden Gem Score.

### CommunityProfileService
Maintains the distribution of community influence for each spot.
- **Key Logic:** Aggregates community IDs from all interactions (reviews/recommendations).

### SpotRankingService
Aggregates all interaction scores into a final set of spot metrics.
- **Key Logic:** Recalculates `local_trust_score`, `hidden_gem_score`, etc.

### PersonalizedRankingService
Generates a user-specific score for spots based on their personal trust graph.
- **Key Logic:** Adds bonuses for spots recommended by followed users or matching the user's community.
