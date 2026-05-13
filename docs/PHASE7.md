# BenLocal - Fase 7 Documentatie: Trust, Reputation, Ranking & Hidden Gem Engine

Dit document beschrijft de resultaten van **Fase 7**, waarin het geavanceerde Trust & Ranking systeem is geïmplementeerd. Dit systeem transformeert BenLocal van een simpele review-app naar een ecosysteem gebaseerd op lokaal vertrouwen en gepersonaliseerde ontdekking.

## Kernconcepten

In Fase 7 stappen we af van simpele gemiddelde ratings. Rankings worden nu bepaald door een complexe mix van factoren:

1.  **Local Trust:** Aanbevelingen van geverifieerde locals wegen zwaarder dan die van incidentele bezoekers.
2.  **User Reputation:** Gebruikers bouwen reputatie op per regio, sector en categorie.
3.  **Hidden Gems:** Spots met sterke lokale validatie maar laag volume worden proactief geïdentificeerd.
4.  **Tourist Saturation:** Het systeem detecteert wanneer een spot gedomineerd wordt door toeristische interacties.
5.  **Personalized Ranking:** Gebruikers zien resultaten die zijn afgestemd op hun Trust Graph (wie zij volgen en hun community-voorkeuren).

---

## Modulaire Services

Er zijn 8 gespecialiseerde services gebouwd die elk een specifiek onderdeel van de engine afhandelen:

1.  **UserReputationService:** Berekent de reputatie van gebruikers op basis van bevestigde aanbevelingen, volgers en kwaliteit van bijdragen.
2.  **TrustGraphService:** Beheert de persoonlijke vertrouwensrelaties tussen gebruikers.
3.  **ReviewWeightService:** Berekent het gewicht van een individuele review op basis van de reputatie van de auteur en of het een geverifieerd bezoek betreft.
4.  **RecommendationScoreService:** Bepaalt de score van een aanbeveling op basis van de lokale status van de gebruiker.
5.  **HiddenGemService:** Detecteert 'verborgen parels' door te zoeken naar spots met een hoge lokale vertrouwensscore maar een relatief laag totaal aantal interacties.
6.  **CommunityProfileService:** Analyseert welke communities (bijv. Nederlands, Belgisch) het meest betrokken zijn bij een spot.
7.  **SpotRankingService:** Aggregeert alle data naar de finale scores op het `Spot` model (`local_trust_score`, `hidden_gem_score`, etc.).
8.  **PersonalizedRankingService:** Genereert een unieke score per gebruiker voor de 'Recommended for You' feed.

---

## Background Processing & Jobs

Vanwege de complexiteit van de berekeningen worden scores asynchroon bijgewerkt:

*   **RecalculateUserReputationJob:** Herberekening van de reputatie van een gebruiker (getriggerd na belangrijke interacties of via scheduler).
*   **RecalculateSpotScoresJob:** Herberekening van alle trust- en ranking-metrics voor een specifieke spot.
*   **Scheduling:** Een dagelijkse taak in `routes/console.php` zorgt ervoor dat alle scores up-to-date blijven.

---

## API Updates (Discovery)

De `/api/discover` endpoint ondersteunt nu geavanceerde sorteermogelijkheden:

*   `recommended_for_you`: Gebruikt de gepersonaliseerde ranking engine.
*   `hidden_gems`: Filtert en sorteert op de Hidden Gem score.
*   `trusted_locals`: Prioriteert spots met de hoogste lokale validatie.
*   `authentic_local`: Combineert hoge lokale trust met lage tourist saturation.
*   `community_match`: Sorteert op basis van de match met de communities van de gebruiker.

---

## Frontend UX Indicatoren

De API resources (`SpotListResource`, etc.) zijn uitgebreid met velden die de PWA kan gebruiken voor labels:

*   **"Trusted Local"**: Voor spots met een zeer hoge `local_trust_score`.
*   **"Hidden Gem"**: Voor spots die door de Hidden Gem engine zijn aangemerkt.
*   **"Tourist Favourite"**: Voor spots met een hoge `tourist_saturation_score`.

---

## Database Wijzigingen

Er is een migratie toegevoegd die scoring-velden toevoegt aan de `spots`, `recommendations` en `reviews` tabellen. Daarnaast is de `user_reputation` tabel uitgebreid om de complexe reputatie-data per dimensie op te slaan.

---

## Testing

De werking van de engine is geverifieerd met een uitgebreide test suite:
`tests/Feature/Phase7RankingTest.php`

Deze test dekt:
*   Hoger gewicht van lokale aanbevelingen.
*   Correcte detectie van Hidden Gems.
*   Impact van Tourist Saturation op de authenticiteitsscore.
*   Gepersonaliseerde rankings per gebruiker.
*   Correcte verwerking van reputatie-penalties bij moderatie-acties.

---

*Status Fase 7: Voltooid*
