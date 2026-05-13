# Frontend (PWA) Documentation

## Trust & Ranking UI

Phase 7 updates the discovery UI to reflect trust metrics.

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
