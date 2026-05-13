# BenLocal Timeline & Feed

The BenLocal feed is the central hub for social discovery. It aggregates activity from across the platform and personalizes it for each user.

## Event Types

The timeline supports the following event types:

*   **recommendation_created**: New spot recommendations.
*   **review_created**: New spot reviews.
*   **review_reaction_created**: Validations (Agree/Disagree) of reviews.
*   **user_followed**: Social connections.
*   **user_tagged_in_review**: When a user is mentioned in a review.
*   **hidden_gem_detected**: Automated system detection of a hidden gem.
*   **spot_status_changed**: When a spot becomes a "Hidden Gem" or "Local Favourite".
*   **campaign_submission_created**: Activity from campaigns like "Tafelen in Tenerife".
*   **campaign_recommendation_created**: Recommendations linked to a specific campaign.
*   **spot_saved**: When a user saves a spot for later.
*   **business_claim_created**: When a business owner claims their spot.
*   **business_claim_approved**: When a claim is verified.

## Payload Structure

Each event has a JSON `payload` that contains context-specific data.

Example `recommendation_created`:
```json
{
  "spot_id": 1,
  "spot_name": "Bodega San Miguel",
  "user_name": "Jan de Hollander",
  "community": "Netherlands",
  "region": "Tenerife",
  "hidden_gem_candidate": true
}
```

## Ranking Logic

The feed is ranked based on:
1.  **Recency**: Newer events appear higher.
2.  **Follows**: Activity from followed users is prioritized.
3.  **Region**: Content in the user's current or residence region (e.g., Tenerife) is favored.
4.  **Community**: Content from the same community (e.g., Dutch users seeing Dutch activity) is boosted.

## Demo Scenarios

See [Seeders Documentation](seeders.md) for details on how to seed specific test scenarios for demo users like Jan, Sofie, and Carlos.
