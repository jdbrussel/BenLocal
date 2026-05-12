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

## Ontwikkelingsfasen

Het project wordt modulair opgebouwd in fasen om schaalbaarheid en kwaliteit te waarborgen.

---

*Dit project wordt ontwikkeld met ondersteuning van AI-assistentie (Junie).*
