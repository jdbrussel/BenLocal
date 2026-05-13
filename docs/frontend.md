# Frontend (PWA) Documentation

## Trust & Ranking UI

Phase 7 updates the discovery UI to reflect trust metrics.

### Check-ins & Verified Visits (Phase 13)
The UI now includes features for users to verify their visits:
- **"I'm here" button:** Located on the spot detail page.
- **Location Permission Modal:** Explains why location is needed for verification.
- **Verified Visit Badges:** Shown on reviews and in the user's visit history.
- **QR Scanner:** Integrated for spot QR token verification.

### Labels & Indicators
- **Trusted Local:** Displayed on spots with `local_trust_score >= 100`.
- **Hidden Gem:** Displayed on spots with `hidden_gem_score >= 70`.
- **Tourist Favourite:** Displayed when `tourist_saturation_score` is significantly higher than local trust.

### Personalized Explanations
The UI should provide context for recommendations:
- "Recommended by people you follow" (if `personalized_score` includes follow bonus).
- "Highly trusted by [Community Name] locals."
- "Authentic local favourite with few tourists."

### Sort Modes
The discovery screen includes a new sort selector:
1. **Recommended for You**
2. **Hidden Gems**
3. **Trusted Locals**
4. **Authentic Local**
5. **Trending**

## CMS & Localization (Phase 16)

The frontend uses localized JSON files for UI strings and calls the CMS API for dynamic page content.

### Localization Files
- `resources/js/locales/en.json`
- `resources/js/locales/nl.json`
- `resources/js/locales/es.json`

### New UI Components
- **Help Center:** Browsing FAQs via `GET /api/cms/faqs`.
- **Legal Pages:** Rendering policies via `GET /api/cms/pages/{slug}`.
- **Translatable CMS Content:** The API returns `title` and `content` based on the `Accept-Language` header, with fallback to English.

## Production Readiness & PWA (Phase 17)

### System State & Messaging
The frontend includes comprehensive system state messaging using the `system.*` translation keys:
- **Loading States:** `system.loading`, `system.pagination_loading`, `system.map_loading`.
- **Offline & Cache:**
    - `system.offline_mode`: Shown when the user's browser is offline.
    - `system.cached_content`: Displayed as a banner when the PWA is serving content from the cache while offline or during slow connections.
- **Connection Issues:** `system.slow_connection` and `system.try_again`.
- **Background Tasks:** `system.queue_processing` and `system.job_*` keys for showing the status of long-running operations.

### PWA Updates
When a new version of the Service Worker is detected, the UI prompts the user:
- **Update Message:** `system.update_available`.
- **Action Button:** `system.update_now`.

### Rate Limiting & Health
- **Rate Limited:** `system.rate_limited` is shown when the backend returns a `429 Too Many Requests` status.
- **System Health:** Monitoring statuses like `system.health_ok`, `system.health_warning`, and `system.health_error`.
