# BenLocal - Documentatie Fase 12: Business Growth & Monetisatie

Dit document beschrijft de plannen en vereisten voor **Fase 12**. In deze fase ligt de focus op het te gelde maken van het platform door middel van premium functionaliteiten voor ondernemers en geavanceerde marketingtools.

## Doelstellingen

1.  **Premium Business Plans:** Introductie van abonnementen (Free vs. Pro/Premium) voor ondernemers.
2.  **Business Analytics:** Diepgaand inzicht voor eigenaren in het succes van hun zaak op BenLocal.
3.  **Marketing & Engagement Tools:** Mogelijkheid voor ondernemers om acties, coupons en speciale evenementen te promoten.
4.  **Priority Discovery:** Betaalde zichtbaarheid (featured spots) in de zoekresultaten en op de kaart.
5.  **Billing Integratie:** Koppeling met een payment provider (bijv. Stripe) voor abonnementsbeheer.

---

## 1. Subscription System (Premium Plans)

Ondernemers kunnen hun geclaimde spot upgraden naar een Premium status.

### Functionaliteiten per plan
*   **Free Plan:** Basisgegevens beheren, reageren op reviews (beperkt), 3 foto's uploaden.
*   **Premium Plan:**
    *   Onbeperkt foto's en video-upload.
    *   Uitgebreide analytics.
    *   Directe messaging met klanten (indien geactiveerd).
    *   Geen advertenties van concurrenten op de eigen spot-pagina.
    *   Toevoegen van 'Special Offers' en 'Events'.

---

## 2. Business Analytics Dashboard

Inzicht in hoe gebruikers interacteren met de zaak.

### Metrics
*   **Spot Views:** Aantal keer dat de detailpagina is bekeken.
*   **Action Clicks:** Kliks op telefoonnummer, website, routebeschrijving.
*   **Review Insights:** Sentiment-analyse van recente reviews en vergelijking met het regionale gemiddelde.
*   **Discovery Source:** Hoe vonden gebruikers de zaak? (via kaart, zoekopdracht, campagne, of feed).

---

## 3. Marketing Tools: Offers & Events

Ondernemers kunnen hun zaak actiever promoten.

### Componenten
*   **Special Offers:** Tijdelijke kortingen of acties (bijv. "Gratis drankje bij vermelding van BenLocal").
*   **Business Events:** Aankondiging van live muziek, thema-avonden, etc.
*   **Featured Status:** Mogelijkheid om de spot tijdelijk te laten opvallen in de 'Discovery' feed of op de kaart met een speciaal icoon.

---

## 4. Billing & Admin Controle

### Integratie
*   **Stripe Integration:** Beheer van abonnementen, facturatie en veilige betalingen.
*   **Billing Portal:** Toegankelijk via het Owner Dashboard voor het wijzigen van betaalgegevens en inzien van facturen.

---

## 5. API & Backend Vereisten

*   **`SubscriptionService`:** Afhandeling van plan-logica en permissies (`canAccessFeature()`).
*   **`AnalyticsService`:** Verzamelen en aggregeren van gebruikersstatistieken voor spots.
*   **`PromotionService`:** Beheer van de levensduur en zichtbaarheid van acties en evenementen.
*   `GET /api/owner/analytics`: Ophalen van statistieken voor het dashboard.
*   `POST /api/owner/offers`: Aanmaken van een nieuwe aanbieding.

---

## 6. Test Scenario's

*   **Feature Gating:** Verifieer dat 'Free' gebruikers geen toegang hebben tot premium statistieken of marketingtools.
*   **Subscription Lifecycle:** Test het upgraden, downgraden en annuleren van een abonnement.
*   **Analytics Accuracy:** Controleer of een klik op de website correct wordt geregistreerd in de analytics database.
*   **Promotion Expiry:** Verifieer dat aanbiedingen automatisch verdwijnen van de frontend wanneer de einddatum is bereikt.
