# API Documentation

## Discovery API

### GET /api/discover
Returns a list of spots based on trust-aware and personalized ranking.

**Query Parameters:**
- `sort`: 
    - `recommended_for_you` (Default): Uses `personalized_score` for authenticated users.
    - `hidden_gems`: Sorts by `hidden_gem_score`.
    - `trusted_locals`: Sorts by `local_trust_score`.
    - `authentic_local`: Filters low tourist saturation and sorts by local trust.
    - `tourist_favourites`: Sorts by tourist saturation.
    - `community_match`: Sorts by `community_match_score`.
- `communities[]`: Filter by community IDs.
- `latitude`, `longitude`, `radius`: Geo-filtering.

**Response Fields (Spot Resource):**
- `trust_score`: Local trust strength.
- `hidden_gem_score`: 0-100 score.
- `tourist_saturation_score`: High value means many tourists.
- `personalized_score`: Present for auth users.
- `is_hidden_gem`: Boolean (score >= 70).
- `is_trusted_local`: Boolean (trust_score >= 100).

## Recommendations & Reviews

### GET /api/spots/{id}/recommendations
Includes `trust_score` and `visibility_score` for each recommendation.

### GET /api/spots/{id}/reviews
Includes `weight` and `visibility_score` for each review.
