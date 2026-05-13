# BenLocal - Fase 6 Documentatie: Recommendations, Reviews & Social Interactions

Dit document beschrijft de resultaten van **Fase 6**, waarin het volledige community-gedreven systeem voor aanbevelingen, reviews en validaties is geïmplementeerd.

## Kernconcepten

BenLocal maakt nu een essentieel onderscheid tussen verschillende vormen van community-interactie:

1.  **Recommendations (Aanbevelingen):** Exclusief voor locals. Zij 'staan in' voor een plek in hun eigen regio.
2.  **Reviews:** Voor iedereen (toeristen en locals). Gebruikers kunnen hun ervaring delen en aangeven of deze de lokale aanbeveling bevestigt of juist tegenspreekt.
3.  **Validatie (Reactions):** De community valideert reviews door te reageren met 'Agree', 'Partly' of 'Disagree'.

---

## Recommendations Systeem

Implementatie van de fundatie voor lokale autoriteit.

*   **Local-Only Logica:** Alleen gebruikers met de status `local` of `verified_local` in een specifieke regio kunnen daar aanbevelingen doen.
*   **Motivatie:** Aanbevelingen bevatten een verplichte motivatie, ondersteund door het meertalige systeem (Spatie Translatable).
*   **Hidden Gem Candidate:** Locals kunnen spots markeren als potentiële 'Hidden Gems', wat de Discovery Engine beïnvloedt.
*   **Endpoints:** Volledige CRUD-ondersteuning via `/api/spots/{spot}/recommendations`.

---

## Uitgebreid Review Systeem

Reviews zijn nu diepgaander en meer verbonden met de community-context.

*   **Dynamic Ratings:** Gebruikers geven ratings op basis van categorie-specifieke kenmerken (bijv. 'Food Quality' voor restaurants, 'Ambiance' voor bars).
*   **Recommendation Confirmation:** Gebruikers kunnen expliciet aangeven of hun ervaring de bestaande lokale aanbevelingen bevestigt (`confirms`), deels bevestigt (`partly`) of tegenspreekt (`contradicts`).
*   **Photo Support:** Mogelijkheid om meerdere foto's per review te uploaden, inclusief onderschriften.
*   **User Tagging:** Gebruikers kunnen andere locals of vrienden taggen in reviewteksten met `@username`, wat sociale interactie stimuleert.

---

## Review Validatie & Reacties

Community-moderatie door middel van user reactions.

*   **Validatie Types:** Gebruikers kunnen reageren op reviews met `Agree`, `Partly agree` of `Disagree`.
*   **Social Proof:** Reactie-overzichten worden getoond bij reviews om de betrouwbaarheid en relevantie voor andere gebruikers te tonen.
*   **Beperkingen:** Gebruikers kunnen niet reageren op hun eigen reviews en er is een limiet van één reactie per review per gebruiker.

---

## Sociale Timeline & Notificaties

Het platform voelt nu "levend" aan door de integratie van events.

*   **Timeline Events:** Automatische creatie van events voor nieuwe aanbevelingen, reviews, reacties en tags.
*   **Notificatie Placeholders:** Voorbereiding voor push-notificaties wanneer een gebruiker getagd wordt of een review wordt gevalideerd.
*   **Sociale Hub:** De basis voor een persoonlijke feed waarin updates van gevolgde locals en relevante regio-activiteiten verschijnen.

---

## Filament Backoffice Updates

Beheerders hebben nu volledige controle over de community-content.

*   **Moderatie Dashboard:** Nieuwe resources voor Recommendations en Reviews met uitgebreide filtermogelijkheden.
*   **Bulk Acties:** Mogelijkheid om content snel goed te keuren, af te wijzen, te verbergen of als 'suspicious' te markeren.
*   **Content Management:** Beheer van review-foto's en inspectie van community-reacties.

---

## Demo Data (Fase 6 Seeders)

De database is gevuld met rijke, realistische data voor testing en demonstratie:

*   **200+ Recommendations:** Van diverse locals met realistische motivaties in NL, EN en ES.
*   **700+ Reviews:** Een mix van toeristen en locals met gevarieerde ratings en bevestigingsstatistieken.
*   **1000+ Review Photos:** Demo-paden met meertalige bijschriften.
*   **3000+ Reactions:** Een realistische verdeling van community-validaties.
*   **User Tags & Timeline:** Een actieve timeline die de sociale dynamiek van het platform simuleert.

---

*Status Fase 6: Voltooid*
