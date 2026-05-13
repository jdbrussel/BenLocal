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
