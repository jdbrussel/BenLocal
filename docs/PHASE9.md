# BenLocal - Documentatie Fase 9: AI Integratie, Campagnes & Business Flow

Dit document beschrijft de plannen en vereisten voor **Fase 9**. In deze fase verschuift de focus naar automatisering door middel van AI, schaalbaarheid van het campagne-systeem en het versterken van de self-service mogelijkheden voor ondernemers.

## Doelstellingen

1.  **AI-gestuurde Automatisering:** Implementatie van het `AITranslationSystem` voor real-time meertaligheid zonder handmatige tussenkomst.
2.  **Campagne Schaalbaarheid:** Optimalisatie van de "Landing naar Aanbeveling" flow en het `CampaignSelectionScore` algoritme.
3.  **Business Self-Service:** Volledige implementatie van de claim-flow en de basis voor premium feature management.

---

## 1. AI Integratie (Translation & Enrichment)

Het hart van de internationale schaalbaarheid van BenLocal.

### Componenten
*   **`AITranslationService`:** Wrapper om LLM API's (bijv. OpenAI/Claude) voor contextbewuste vertalingen.
*   **`HasAiTranslations` Trait:** Voor eenvoudige integratie in modellen zoals `Spot`, `Review` en `Recommendation`.
*   **`TranslateModelFieldJob`:** Queued jobs voor asynchrone vertaling van content.

### Functionaliteit
*   Automatische detectie van brontaal.
*   Vertaling naar doelstalen (NL, EN, ES, DE, FR) bij creatie of update.
*   AI Enrichment: Automatisch aanvullen van ontbrekende spot-informatie op basis van webreferenties.

---

## 2. Campagne Systeem & Engagement

Uitbreiding van het systeem om tijdelijke of regio-specifieke promoties te ondersteunen.

### Componenten
*   **`CampaignSelectionScoreService`:** Algoritme om de beste spots te selecteren voor een specifieke campagne (bijv. "Tafelen in Tenerife").
*   **Landing Page Flow:** Geoptimaliseerde Vue/Inertia frontend voor campagne-deelnemers.
*   **AI Spot Matching:** Matching van vrije tekst invoer van gebruikers aan bestaande spots in de database.

---

## 3. Business Claim Flow & Management

Ondernemers de controle geven over hun eigen presentatie op het platform.

### Functionaliteit
*   **Claim Proces:** Self-service flow waarbij eigenaren eigenaarschap kunnen bewijzen.
*   **Claim Verificatie:** Administratieve workflow in Filament voor het goedkeuren/afwijzen van claims.
*   **Business Dashboard (Basis):** Eerste aanzet tot een interface voor geverifieerde eigenaren om spot-details (openingstijden, foto's) te beheren.
*   **Notification Loop:** E-mail notificaties voor statuswijzigingen van claims.

---

## API & Backend Vereisten

*   `POST /api/claims`: Indienen van een nieuwe business claim.
*   `GET /api/campaigns/{slug}`: Ophalen van campagne-specifieke data en spots.
*   Artisan commands voor bulk AI vertalingen.

---

## Test Scenario's

*   **AI Vertaling:** Verifieer dat een Spaanse review correct en contextueel vertaald wordt naar het Nederlands.
*   **Claim Flow:** Test de overgang van 'pending' naar 'approved' en de bijbehorende toegangsrechten.
*   **Campagne Score:** Controleer of de `CampaignSelectionScoreService` de juiste spots prioriteert op basis van lokale validatie.

---

*Status Fase 9: Geïmplementeerd*

### Geïmplementeerde Componenten
- **`CampaignLandingController`**: Beheert de landing page en submission flow.
- **`CampaignSubmissionService`**: Verwerkt inzendingen van gebruikers (met placeholder AI).
- **`CampaignSpotMatchingService`**: Zoekt naar bestaande spots op basis van gebruikersinvoer.
- **`CampaignRecommendationService`**: Zet goedgekeurde inzendingen om naar officiële aanbevelingen.
- **`CampaignSelectionService`**: Beheert shortlisting, magazine selectie en exports.
- **PWA Screens**: `Campaign/Landing.vue` en `Campaign/Success.vue` met Inertia.js.
- **Filament Admin**: Uitgebreide resources voor Campagnes, Inzendingen en Aanbevelingen met custom acties.
