# BenLocal - Fase 5 Documentatie: Public API & Discovery Engine

Dit document beschrijft de resultaten van **Fase 5**, gericht op de Public API fundatie en de Discovery Engine die de PWA frontend van echte backend-data voorziet.

## API Architectuur

De API is ontworpen volgens moderne REST-principes met een sterke focus op performance en schaalbaarheid.

*   **Laravel API Routes:** Centraal beheer van endpoints in `routes/api.php`.
*   **Sanctum Authentication:** Beveiligde toegang voor gepersonaliseerde features zoals 'Saved Spots'.
*   **API Resources:** Consistente transformatie van database-modellen naar JSON responses, inclusief automatische afhandeling van meertalige velden.
*   **Service Layer:** Business logica voor zoekopdrachten en filters is ondergebracht in gespecialiseerde services zoals `DiscoveryService`.

---

## Discovery Engine & Zoeken

De kern van de BenLocal ervaring is het ontdekken van de juiste 'spots'.

*   **Discovery Endpoint:** `GET /api/discover` ondersteunt complexe filtering op regio, area, place, sector en categorie.
*   **Dynamische Filters:** Ondersteuning voor filtering op `spec_values` (JSON), zoals keukenstijl, prijsklasse en faciliteiten (terras, zeezicht).
*   **Search Engine:** Een krachtig `GET /api/search` endpoint voor het doorzoeken van spots, regio's en gebruikers met ondersteuning voor partial matches.
*   **Caching:** Basis caching strategie voor veelgebruikte data zoals regio's en categorieën om de response-tijd te optimaliseren.

---

## Locatie-gebaseerd Zoeken

Integratie van geografische data voor een optimale lokale ervaring.

*   **Haversine Formule:** Implementatie van afstandsberekeningen in SQL om spots in de buurt te vinden op basis van breedtegraad en lengtegraad.
*   **Map Markers:** Een specifiek lichtgewicht endpoint (`GET /api/map/spots`) voor het efficiënt renderen van grote hoeveelheden markers op de interactieve kaart.
*   **Hiërarchie:** Naadloze navigatie door de geografische boom: Regio -> Area -> Place.

---

## Meertaligheid in de API

De API respecteert de taalvoorkeuren van de gebruiker in elke response.

*   **Resolved Translations:** Translatabele JSON-velden worden automatisch omgezet naar de actieve taal (NL, EN, ES).
*   **Fallback Logic:** Intelligente terugval naar de standaardtaal als een vertaling ontbreekt.
*   **Translation Indicators:** API responses bevatten indicators wanneer tekst automatisch is vertaald of wanneer de originele taal wordt getoond.

---

## Saved Spots (Favorieten)

Gebruikers kunnen hun favoriete locaties opslaan voor later gebruik.

*   **SavedSpot Model:** Nieuwe database-architectuur voor het koppelen van gebruikers aan spots.
*   **Endpoints:** Volledige CRUD-ondersteuning via `/api/saved-spots` voor ingelogde gebruikers.
*   **Frontend Sync:** Directe visuele feedback in de PWA wanneer een spot wordt opgeslagen of verwijderd.

---

## Frontend API Integratie

De PWA frontend is volledig verbonden met de nieuwe API endpoints.

*   **ApiService.js:** Een centrale JavaScript service voor alle communicatie met de backend.
*   **Real-time Data:** Discover, Search en Saved pagina's tonen nu actuele data uit de database in plaats van statische placeholders.
*   **Loading & States:** Implementatie van skeleton loaders en empty states voor een vloeiende gebruikerservaring tijdens data-fetching.

---

## Demo Data & Discovery Testing

De `Phase5DiscoverySeeder` zorgt voor een rijke testomgeving.

*   **Uitgebreid Tenerife:** Toevoeging van nieuwe gebieden zoals El Médano, Garachico en Icod de los Vinos.
*   **Realistische Spots:** 150+ nieuwe locaties met gedetailleerde `spec_values` voor het testen van alle filtercombinaties.
*   **Interactie Data:** 1500+ aanbevelingen en 3800+ reviews om de discovery algoritmes en sociale filters te valideren.
*   **Saved States:** 800+ vooraf gegenereerde 'saved spots' om de gepersonaliseerde overzichten te testen.

---

*Status Fase 5: Voltooid*
