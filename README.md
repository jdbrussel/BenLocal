# BenLocal - Multilingual Community Recommendation Platform

BenLocal is een schaalbaar meertalig community-gedreven aanbevelingsplatform gebouwd met de nieuwste technologieën.

## Core Concept

BenLocal is GEEN traditioneel reviewplatform. Het is gebaseerd op lokaal vertrouwen, culturele filtering en sociale validatie.

### Aanbevelingen vs. Reviews
- **Aanbevelingen**: Alleen locals kunnen plekken aanbevelen binnen hun eigen regio ("Ik sta achter deze plek als local").
- **Reviews**: Bezoekers en locals kunnen reviews schrijven na een bezoek om aanbevelingen te bevestigen of tegen te spreken.
- **Validatie**: Gebruikers kunnen reviews valideren (mee eens/oneens), wat zorgt voor een reputatiesysteem.

## Architectuur

### Hiërarchie
Region → Area → Place / Zone

### Communities
Land-gebaseerde communities (Spanje, Nederland, België, Duitsland, UK, etc.). Gebruikers kunnen communities in- of uitschakelen voor gepersonaliseerde rankings.

### Sectoren & Categorieën
- **Food & Drinks**: Restaurants en Bars.
- Dynamische filter- en ratingspecificaties per categorie (geen hardcoded velden).

## Documentatie

Gedetailleerde documentatie over de werking van het systeem is te vinden in de `docs` map:

- [Project Documentatie Index](docs/README.md)
- [Project Setup](docs/setup.md): Installatie en configuratie.
- [Architectuur](docs/architecture.md): Regio's, communities en categorieën.
- [Core Features](docs/features.md): Aanbevelingen, AI vertalingen en reputatie.
- [Business Rules](docs/business_rules.md): Logica achter scoring en ranking.
- [Algoritmes](docs/algorithms.md): Gedetailleerde scoring specificaties.

## Tech Stack

- **Framework**: Laravel 12 (PHP 8.3+)
- **Admin Panel**: Filament v5
- **Database**: MySQL of PostgreSQL (momenteel SQLite voor dev)
- **Frontend**: TailwindCSS & Mobile-first PWA
- **Meertaligheid**: Spatie Laravel Translatable (JSON fields)
- **AI**: AI-ondersteunde vertalingen en spot enrichment

## Systeemonderdelen

- **AI Translation System**: `AITranslationService`, `TranslateModelFieldJob`.
- **User Reputation**: Gebruikers bouwen reputatie op per regio, categorie en community.
- **Hidden Gems**: Dynamisch systeem voor plekken met weinig maar hoogwaardige lokale steun.
- **Campaign System**: Generieke campagnes (bijv. "Tafelen in Tenerife") voor user engagement.
- **Business Claim Flow**: Eigenaren kunnen hun spot claimen zonder dat dit de ranking beïnvloedt.

## Scoring & Algoritmes

Het hart van BenLocal is het geavanceerde scoringssysteem dat zorgt voor betrouwbare en gepersonaliseerde aanbevelingen.

Belangrijke principes:
- **Regio Status Weging**: Locals en geverifieerde locals hebben meer invloed op de score.
- **Community Match**: Rankings worden gepersonaliseerd op basis van de communities die een gebruiker volgt.
- **Persoonlijk Vertrouwen**: Het volgen van andere gebruikers geeft een boost aan hun reviews in jouw overzicht.
- **Hidden Gem Score**: Een algoritme dat plekken identificeert met hoge kwaliteit maar lage massa-populariteit.
- **Reputatie**: Gebruikers bouwen reputatie op per regio, sector en categorie.

Zie [Algoritmes](docs/algorithms.md) voor de volledige technische specificatie van alle scoring services.

## Ontwikkelingsfasen

Het project wordt modulair opgebouwd in verschillende fasen:
1. **[Fase 1: Core Architectuur](docs/PHASE1.md)**: Laravel 12 setup, meertaligheid (JSON), geografische hiërarchie, modellen en migraties.
2. **[Fase 2: Admin Panel](docs/PHASE2.md)**: Filament v5 integratie, CRUD beheer voor locaties, taxonomie en spots.
3. **[Fase 3: Auth & Localization](docs/PHASE3.md)**: Authenticatie (Sanctum/Socialite), meertaligheidssysteem, gebruikersvoorkeuren en cookie consent.
4. **[Fase 4: PWA Frontend](docs/PHASE4.md)**: Mobile-first PWA frontend met Vue 3, onboarding flow en core UX componenten.
5. **[Fase 5: Public API & Discovery](docs/PHASE5.md)**: Public API routes, Discovery Engine en frontend-backend integratie.
6. **[Fase 6: Recommendations & Reviews](docs/PHASE6.md)**: Aanbevelingen door locals, reviews, foto's, validaties en sociale interacties.
7. **[Fase 7: Trust & Ranking Engine](docs/PHASE7.md)**: Geavanceerde trust-based ranking, persoonlijke trust graph en hidden gems.
8. **[Fase 8: Timeline & Social Feed](docs/PHASE8.md)**: Gepersonaliseerde feed, activity tracking en sociale interactie.
9. **[Fase 9: AI, Campagnes & Business](docs/PHASE9.md)**: AI-gestuurde vertalingen, campagne optimalisatie en business claim flows.

---

*Dit project wordt ontwikkeld met ondersteuning van AI-assistentie (Junie).*
