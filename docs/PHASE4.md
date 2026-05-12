# BenLocal - Fase 4 Documentatie: PWA Frontend

Dit document beschrijft de resultaten van **Fase 4**, gericht op de publieke PWA frontend fundatie en de UX architectuur.

## Frontend Stack

De frontend is gebouwd met een moderne, mobiel-eerst benadering voor een app-achtige ervaring.

*   **Inertia.js + Vue 3:** Voor een naadloze Single Page Application (SPA) ervaring met Laravel.
*   **Tailwind CSS:** Voor een responsief en consistent design.
*   **Lucide Icons:** Voor moderne en duidelijke icoongebruik.
*   **Ziggy:** Voor het gebruik van Laravel routes in Vue componenten.

---

## PWA Functionaliteit

BenLocal is geconfigureerd als een Progressive Web App (PWA).

*   **Manifest.json:** Definieert de app-naam, iconen, themakleur en display mode.
*   **Service Worker:** Basisregistratie voor offline ondersteuning en caching.
*   **Mobile Optimized:** Specifieke meta-tags voor iOS en Android integratie (splash screens, status bar styling).

---

## Layouts & Navigatie

De app gebruikt een hiërarchische layout-structuur die is geoptimaliseerd voor mobiel gebruik.

*   **AppLayout:** De basis layout met een persistente **Bottom Navigation** voor snelle toegang tot Discover, Feed, Search, Saved en Profile.
*   **GuestLayout:** Voor publieke schermen (login, registratie).
*   **OnboardingLayout:** Speciaal ontworpen voor een afleidingsvrije onboarding flow.
*   **Navigation:** Ondersteuning voor context-gevoelige menu's en sticky filters.

---

## Onboarding Flow

Een stapsgewijze interface om nieuwe gebruikers te verwelkomen en te configureren.

*   **Stappen:**
    1.  Welkom & Introductie.
    2.  Taaldetectie & Bevestiging.
    3.  Cookie Consent (Bottom Sheet).
    4.  Regio Selectie.
*   **UX:** Gebruik van voortgangsindicatoren en mobiel-vriendelijke kaarten.

---

## Core Pagina's & UX Fundatie

De belangrijkste schermen zijn opgezet als fundatie voor toekomstige functies.

*   **Discover:** Het hoofdscherm met regio-selectie, categorie-filters en een toggle tussen **Lijst- en Kaartweergave**.
*   **Map View:** Integratie van **Leaflet** voor interactieve kaarten met markers voor spots.
*   **Search:** Zoekfunctionaliteit voor spots, regio's en gebruikers.
*   **Spot Detail:** Uitgebreide weergave van locaties, inclusief community-aanbevelingen en reviews.
*   **User Profile:** Publieke profielen met statistieken over aanbevelingen en volgers.

---

## Meertaligheid & Thema in Frontend

De lokalisatie- en thema-instellingen uit Fase 3 zijn volledig geïntegreerd in de frontend.

*   **Translation Helper:** Een custom `trans()` helper in Vue voor het ophalen van vertalingen.
*   **Theme Switcher:** Ondersteuning voor Light, Dark en System mode, direct zichtbaar in de interface.
*   **Localized Content:** Automatische weergave van meertalige database-velden op basis van de geselecteerde taal.

---

## Demo Data & UX Testing

Om de frontend volledig te kunnen testen, is een uitgebreide demo-seeder (`Phase4UXSeeder`) toegevoegd.

*   **Ecosysteem:** Genereert een realistische sociale graaf met 40+ actieve gebruikers, 100+ follows en actieve feeds.
*   **Geografische Densiteit:** 60+ spots verdeeld over specifieke regio's in Tenerife (Costa Adeje, Playa de las Américas, etc.) met realistische coördinaten voor de kaartweergave.
*   **Content:** 200+ aanbevelingen (alleen door locals) en 400+ reviews met reacties en geverifieerde bezoeken.
*   **Edge Cases:** Bevat spots zonder afbeeldingen, controversiële spots met gemengde reviews, en meertalige content met fallback scenario's.
*   **Campagnes:** Demo data voor de "Tafelen in Tenerife" campagne, inclusief inzendingen en shortlists.

---

*Status Fase 4: Voltooid*
