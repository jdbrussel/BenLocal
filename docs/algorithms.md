# BenLocal Scoring & Algoritmes

Dit document bevat de gedetailleerde specificaties voor alle scoring logica en algoritmes binnen het BenLocal platform. Alle scoring logica moet worden ondergebracht in service classes.

## Overzicht van Services
- `ReviewWeightService`
- `RecommendationScoreService`
- `UserReputationService`
- `SpotRankingService`
- `HiddenGemService`
- `CommunityProfileService`
- `PersonalizedRankingService`
- `CampaignSelectionScoreService`

Scores lopen standaard van 0 tot 100.

---

## 1. User Region Status Weight
Gebruikers hebben een status per regio die hun invloed bepaalt.

**Gewichten:**
- `verified_local`: 1.00
- `local`: 0.85
- `regular_visitor`: 0.60
- `visitor`: 0.35
- `tourist`: 0.20
- `unknown`: 0.10

*Alleen locals of verified_locals kunnen officiële aanbevelingen doen.*

---

## 2. Community Match Weight
Personaliseert rankings op basis van community-voorkeuren.

- Exacte match: 1.00
- Verschillend maar ingeschakeld: 0.75
- Uitgeschakelde community: 0.20
- Onbekend: 0.50

---

## 3. Personal Trust Weight
Boost voor gevolgde gebruikers.
- Gevolgd: +0.30 boost
- Gevolgd met hoge reputatie: +0.45 boost
- Maximaal gewicht gecapt op 1.30.

---

## 4. Review Weight
Bepaald door status, community, vertrouwen, verificatie, reputatie en recentheid.

**Factoren:**
- Geverifieerd bezoek: 1.20 (vs 1.00 ongeverifieerd)
- Reputatie: 0.75 (0 rep) tot 1.25 (100 rep)
- Validatie (reactions): Agree (+1), Partly (+0.25), Disagree (-1)
- Recentheid: 1.00 (0-3 mnd) tot 0.60 (>24 mnd)

---

## 5. Review Score
Gebruikt dynamische categorie rating specs.

**Voorbeeld Restaurants:**
- Food Quality: 1.4
- Service: 1.0
- Atmosphere: 0.8
- Value for Money: 1.1

---

## 6. Recommendation Score
Krachtiger dan een review (actieve aanbeveling).
Afhankelijk van status, reputatie, community match, validatie door anderen en motivatie.

---

## 7. Spot Base Score
De fundamentele score van een plek:
- Recommendations component: 45%
- Review component: 30%
- Local validation component: 15%
- Verified visit component: 5%
- Recency component: 5%

---

## 8. Personalized Spot Score
Individuele ranking per gebruiker gebaseerd op de Base Score plus bonussen voor community match, gevolgde gebruikers, afstand en filters.

---

## 9. Hidden Gem Score
Focus op kwaliteit, lokale steun en lage "tourist noise".
- 80+ = Hidden Gem
- 65-79 = Hidden Gem Candidate

---

## 10. Local Favourite Score
Focus op volume en consistentie van lokale aanbevelingen.

---

## 11. Community Profile Validation
Vergelijking tussen de geclaimde community-verdeling en de werkelijke perceptie door reviews/aanbevelingen.

---

## 12. User Reputation Score
Berekend per regio/sector/categorie.
- 50+ = Active Local
- 65+ = Trusted Local
- 80+ = Top Local
- 90+ = Curator

---

## 13. Recommendation Validation
Validatie door bevestigende reviews en andere locals.

---

## 14. Review Visibility Score
Bepaalt of een review standaard wordt getoond of ingeklapt.

---

## 15. Campaign Selection Score
Voor het selecteren van spots voor marketing/magazines (bijv. "Tafelen in Tenerife").

---

## 16. Search Intent Boosts
Ondersteunde intents:
- `traditional_local`
- `international_quality`
- `community_favourite`
- `bars_drinks`
- `hidden_gems`

---

## 17. Distance / Location Score
Kleine bonus voor nabijheid (max +5), maar mag kwaliteit niet domineren.

---

## 18. Score Recalculation
Gebeurt via queued jobs en scheduled commands (`benlocal:recalculate-*`).

---

## 19. Database Score Tabellen
- `spot_scores`
- `user_reputation_scores`
- `review_scores`
- `recommendation_scores`

---

## 20. Belangrijke Principes
- Scores moeten uitlegbaar zijn.
- Geen "black-box" ranking.
- Bedrijfseigenaren kunnen organische scores niet beïnvloeden.
- Geclaimde spots ranken niet automatisch hoger.
