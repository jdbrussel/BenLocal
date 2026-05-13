# BenLocal - Phase 8 Documentation: Timeline, Social Feed & Activity System

This document describes the results of **Phase 8**, in which the personalized timeline and feed system was implemented. This system connects users with real-time local activity and prioritizes content based on trust and relevance.

## Core Concepts

In Phase 8, BenLocal moves from a discovery-focused app to a social-local ecosystem. The feed provides a stream of events that are personally relevant to each user.

1.  **Personalized Feed:** Users see activity from people they follow and in regions they are interested in first.
2.  **Timeline Events:** A generic event system that captures all significant interactions.
3.  **Ranking Engine:** A multi-factor ranking algorithm that balances recency with trust (follows, locals, community).
4.  **Activity Feeds:** Users can view their own activity or others' public activity.

---

## Modular Services

Three new services handle the feed system:

1.  **TimelineEventService:** Centralized service to create and manage activity events across the platform.
2.  **FeedService:** Orchestrates the retrieval of events, applying filters and calling the personalization engine.
3.  **PersonalizedFeedService:** The ranking engine that calculates a `rank_score` for each event based on user context.

---

## Event Types

The feed currently supports the following event types:

*   **recommendation_created:** New spot recommendations.
*   **review_created:** New spot reviews.
*   **review_reaction_created:** Validations of reviews by other users.
*   **follow_created:** Social connections between users.
*   **hidden_gem_update:** Automated notifications when a spot reaches "Hidden Gem" status.

---

## API Endpoints

*   `GET /api/feed`: Returns the personalized feed for the authenticated user (or general latest feed for guests).
*   `GET /api/users/{user}/activity`: Returns the activity history for a specific user.

---

## Frontend Implementation

The feed is implemented using Inertia.js/Vue:

*   **Feed Page:** Located at `/feed`, featuring infinite scroll and pull-to-refresh.
*   **FeedItem Component:** A versatile card component that renders different UI for each event type.
*   **Translations:** Full support for English and Dutch feed labels.

---

## Filament Administration

The `TimelineEventResource` in the Admin panel provides:

*   **Moderation Visibility:** See all activity events in a centralized list.
*   **Debug View:** Inspect the raw JSON payload of any event.
*   **Filtering:** Filter by event type and region.

---

## Testing

A new test suite has been added: `tests/Feature/Phase8FeedTest.php`

This covers:
*   Feed relevance and event visibility.
*   Prioritization of followed users.
*   Region filtering.
*   Guest fallback logic.
*   User activity endpoints.

---

*Status Phase 8: Completed*
