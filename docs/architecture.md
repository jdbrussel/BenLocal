# Architectuur van BenLocal

BenLocal is ontworpen als een schaalbaar, meertalig platform met een sterke focus op lokale gemeenschappen.

## Geografische Hiërarchie

Het systeem hanteert een strikte hiërarchie voor locaties om precisie in aanbevelingen en filtering te garanderen:

1.  **Region (Regio):** Hoogste niveau (bijv. Tenerife, Amsterdam, Mallorca).
2.  **Area (Gebied):** Onderverdeling van de regio (bijv. Costa Adeje, Jordaan).
3.  **Place / Zone:** Specifieke plekken of zones binnen een gebied (bijv. Puerto Colón).

Local-status van een gebruiker is altijd gekoppeld aan een **Region**.

## Communities

Communities zijn land-gebaseerd en beïnvloeden de relevantie van content:

*   **Beschikbare Communities:** Spanje/Canarische Eilanden, Nederland, België, Duitsland, Verenigd Koninkrijk, Overig.
*   **Functionaliteit:**
    *   Gebruikers kunnen communities in- of uitschakelen.
    *   Beïnvloedt rankings, aanbevelingen en feeds.
    *   Biedt een culturele filter over de data.

## Sectoren & Categorieën

BenLocal start met de sector **Food & Drinks**.

### Categorieën:
1.  **Restaurants**
2.  **Bars**

### Dynamische Filter Specs (Geen hardcoded types):
In plaats van een statische `spot_types` tabel, gebruiken we dynamische categorie filter specs.
*   **Restaurants:** Bijv. Guachinche, Bodega, Tapas, Asador, Fine dining.
*   **Bars:** Bijv. Beachbar, Cocktailbar, Pub, Bierbar, Loungebar.

## Trust & Ranking Engine (Phase 7)

Phase 7 introduces a complex trust-based ranking system that replaces simple average ratings.

### Core Components
- **User Reputation:** Calculated per region, sector, category, and community.
- **Trust Graph:** Personal weights based on follows and community alignment.
- **Hidden Gem Engine:** Identifies spots with strong local support but low overall volume.
- **Tourist Saturation:** Detects spots heavily dominated by non-local interactions.
- **Spot Scoring:** Multi-factor scoring (Recommendation, Review, Local Trust, Hidden Gem, Community Match).
- **Personalized Ranking:** User-specific discovery results based on trust graph and preferences.

### Modular Services
The system uses 8 specialized services to handle different aspects of trust and ranking:
1. `UserReputationService`
2. `TrustGraphService`
3. `ReviewWeightService`
4. `RecommendationScoreService`
5. `HiddenGemService`
6. `CommunityProfileService`
7. `SpotRankingService`
8. `PersonalizedRankingService`

## Timeline & Feed System (Phase 8)

Phase 8 introduces a personalized timeline that provides users with a real-time stream of relevant local activity.

### Key Features
- **Personalized Feed:** Ranked based on followed users, current region, community interests, and language.
- **Timeline Events:** Generic event system capturing recommendations, reviews, reactions, follows, and status updates.
- **Infinite Scroll:** Optimized API and frontend for seamless content discovery.
- **Region Awareness:** Prioritizes events happening in the user's active region.

### Core Services
- `TimelineEventService`: Handles creation and management of activity events.
- `FeedService`: Orchestrates feed delivery and filtering.
- `PersonalizedFeedService`: Implements the ranking algorithm for the user's primary feed.
