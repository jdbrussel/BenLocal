# Changelog

## [MVP Final Audit] - 2026-05-14

### Added
- **Production Seeding:** Implemented `ProductionSeeder` and `TenerifeSpotSeeder` with 80+ realistic spots in Tenerife (Food & Drinks).
- **Hardened Test Suite:** Added `ResponseIntegrityTest` to ensure root and onboarding routes remain stable.
- **Tenerife Focus:** Verified and seeded specific regions (Costa Adeje, Los Cristianos, Playa de las Américas, etc.) and categories (Restaurants, Bars).

### Fixed
- **API Stability:** Fixed 500 errors in `CategoryController` and `DiscoveryController` by removing incompatible `Cache::tags()` (switched to standard caching for database/file drivers).
- **Service Worker:** Fixed 404/302 errors in `sw.js` related to Vite versioned assets and redirect handling.
- **Seeder Reliability:** Fixed `UniqueConstraintViolationException` in `AnonymizedUserSeeder` by ensuring proper community associations.
- **Type Errors:** Resolved critical `__PHP_Incomplete_Class` errors caused by corrupted session/cache data through a full system purge.

### Changed
- **API Resources:** Synchronized `SpotDetailResource`, `SpotListResource`, and `TimelineEventResource` with frontend data requirements.
- **Translations:** Enriched PHP translation files (`ui.php`, `system.php`) with frontend keys and optimized `HandleInertiaRequests` for translation sharing.
- **Onboarding:** Enhanced `OnboardingController` to persist user choices (language, region, community) in session and database.
- **Models:** Added `active()` scope to `Region` and `Community` models for better query handling.
- **Database Architecture:** Verified that Spot Types are dynamically handled via `category_filter_specs` instead of hardcoded tables.
- **CMS Audit:** Verified all 15+ mandatory legal and help pages are present and translatable in EN, NL, and ES.
- **Filament Audit:** Verified all 30+ resources are functional and properly configured for the MVP launch.

## [Phase 17] - 2026-05-14

### Added
- **Production Readiness:** Comprehensive caching, queueing, and performance optimizations.
- **Multilingual System Translations:** 30+ new system-level translation keys for both PHP and JSON (`system.php`, `locales/*.json`).
- **Caching Layer:** Redis-backed `Cache::tags` implementation for Discovery, Feed, and Spot APIs.
- **Background Task Management:** Dedicated CLI commands for ranking, reputation, and cache maintenance.
- **Health Monitoring:** New `/api/health` endpoint with multi-component connectivity checks.
- **PWA Resilience:** UI support for offline mode, cached content banners, and slow connection warnings.
- **Performance Documentation:** New technical docs for `performance.md`, `queues.md`, and `cache.md`.
- **Benchmark Seeders:** Expanded seeder suite for large-scale performance testing (100k+ events), realistic geo-clustering in Tenerife, and queue/cache scenario testing.

## [Phase 16] - 2026-05-14

### Added
- Multilingual CMS for public pages and FAQs.
- `Faq` model and migration.
- `FaqResource` and updated `PageResource` in Filament.
- `CmsController` with public API endpoints for localized content.
- `CmsSeeder` and `FaqSeeder` with full legal and help content in EN, NL, ES.
- `CmsTest` for verifying page rendering and publishing logic.
- Multilingual translation files for pages (`lang/{en,nl,es}/pages.php`).
- UI translation keys for CMS components in `resources/js/locales/{en,nl,es}.json`.
- Documentation for CMS, legal pages, and frontend localization.

## [Phase 15] - 2026-05-14
### Added
- **GDPR & Privacy Management:** Full data portability and right-to-erasure flows.
- **Core GDPR Services:**
    - `DataExportService`: Automated extraction of personal data.
    - `UserAnonymizationService`: Scrubbing personal identifiers while preserving content integrity.
    - `AccountDeletionService`: Coordinating the account deletion lifecycle.
    - `ConsentAuditService`: Tamper-evident logging of privacy actions.
- **Privacy Controls:** Granular visibility settings for profiles, location, and reviews.
- **Filament GDPR Resources:** Dedicated admin tools for Export Requests, Deletion Requests, and Privacy Audit Logs.
- **Enhanced GDPR Seeders:** 5 new specific seeders for realistic testing of exports, deletions, anonymization, and consent history.
- **Documentation:** New `docs/gdpr.md`, `docs/privacy.md`, `docs/account-lifecycle.md` and updated `docs/seeders.md`.

## [Phase 13] - 2026-05-13
### Added
- **Visit Verification System:** GPS and QR-based check-ins to boost review credibility.
- **SpotVisitService & VisitVerificationService:** Core logic for handling and verifying visits.
- **API Endpoints:** `POST /api/spots/{spot}/check-in`, `POST /api/spots/{spot}/qr-check-in`, and `GET /api/me/visits`.
- **QR Token Management:** Spots can now generate and manage secure QR tokens for verification.
- **Suspicious Activity Detection:** Automatic flagging of visits that appear too far from spot coordinates.
- **Multilingual Support:** Complete translations for NL, EN, and ES for all visit-related features.
- **Enhanced Seeders:** Realistic demo data for GPS/QR check-ins, suspicious visits, and linked reviews.
- **Documentation:** New `docs/check-ins.md` and updates to API/Frontend/Seeder docs.
- **Testing:** Comprehensive feature tests for GPS distance validation and QR token logic.

## [Phase 12] - Planned
### Added
- Business Premium/Pro Plans documentation.
- Business Analytics and Engagement tool requirements.
- Marketing & Promotion (Offers/Events) feature specifications.
- Billing & Stripe integration roadmap.

## [Phase 11] - 2026-05-13
### Added
- Business Claim system with secure `ClaimToken` logic.
- Owner Dashboard (Filament Panel) for business management.
- Owner role system (Owner, Manager, Editor).
- Owner review responses with translatable support.
- Comprehensive demo seeders for claim flows and owner interactions.
- Admin approval queue for business claims.

## [Phase 10] - 2026-05-13
### Added
- Notification system for business claims.
- Enhanced moderation tools.

## [Phase 9] - 2026-05-13
### Planned
- **AI Integration:** Automated context-aware translations for spots, reviews, and recommendations.
- **Campaign System:** Enhanced selection scoring and optimized "Landing to Recommendation" user flow.
- **Business Claim Flow:** Refined self-service ownership verification and basic business management dashboard.

## [Phase 8] - 2026-05-13
### Added
- **Timeline & Social Feed:** Centralized system for tracking and displaying platform activity.
- **3 New Services:**
    - `TimelineEventService`: Handles event creation and management.
    - `FeedService`: Orchestrates feed retrieval and filtering.
    - `PersonalizedFeedService`: Ranks feed items based on follows, region, and community.
- **Activity Events:** Support for 12+ event types (recommendations, reviews, follows, hidden gems, campaigns).
- **Frontend UI:** Vue 3 / Inertia.js feed page with infinite scroll and dynamic event cards.
- **Filament Integration:** `TimelineEventResource` for admin moderation and payload debugging.
- **Enhanced Seeders:** Rich demo data for specific user scenarios (Jan, Sofie, Carlos, etc.) to test personalization.
- **Testing:** Comprehensive test suite for feed relevance, prioritization, and guest access.

## [Phase 7] - 2026-05-13
### Added
- **Trust Engine:** Complete modular system for calculating trust and ranking.
- **8 New Services:** 
    - `UserReputationService`: Granular reputation per region/category.
    - `TrustGraphService`: Personal trust weights.
    - `ReviewWeightService`: Dynamic influence per review.
    - `RecommendationScoreService`: Trust-weighted recommendations.
    - `HiddenGemService`: Automated hidden gem detection.
    - `CommunityProfileService`: Dynamic spot community profiling.
    - `SpotRankingService`: Global spot metric aggregation.
    - `PersonalizedRankingService`: User-specific result ranking.
- **Background Jobs:** 
    - `RecalculateUserReputationJob`
    - `RecalculateSpotScoresJob`
- **Database Fields:** New scoring columns in `spots`, `recommendations`, and `reviews` tables.
- **API Improvements:** Support for `personalized_score` and new sort modes in `/api/discover`.
- **Testing:** Comprehensive ranking and reputation tests.

### Changed
- `DiscoveryService` updated to use trust-aware and personalized ranking.
- `SpotListResource`, `RecommendationResource`, and `ReviewResource` now expose trust metrics.
- Scheduler updated with daily recalculation tasks.
