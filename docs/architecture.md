# Architectuur van BenLocal

BenLocal is ontworpen als een schaalbaar, meertalig platform met een sterke focus op lokale gemeenschappen.

## Geografische Hiërarchie

Het systeem hanteert een strikte hiërarchie voor locaties om precisie in aanbevelingen en filtering te garanderen:

1.  **Region (Regio):** Hoogste niveau (bijv. Tenerife, Amsterdam, Mallorca).
2.  **Area (Gebied):** Onderverdeling van de regio (bijv. Costa Adeje, Jordaan).
3.  **Place / Zone:** Specifieke plekken of zones binnen een gebied (bijv. Puerto Colón).

Local-status van een gebruiker is altijd gekoppeld aan een **Region**.

## Communities

Communities zijn land-gebaseerd en beïnvloeden de relevantie van content:

*   **Beschikbare Communities:** Spanje/Canarische Eilanden, Nederland, België, Duitsland, Verenigd Koninkrijk, Overig.
*   **Functionaliteit:**
    *   Gebruikers kunnen communities in- of uitschakelen.
    *   Beïnvloedt rankings, aanbevelingen en feeds.
    *   Biedt een culturele filter over de data.

## Sectoren & Categorieën

BenLocal start met de sector **Food & Drinks**.

### Categorieën:
1.  **Restaurants**
2.  **Bars**

### Dynamische Filter Specs (Geen hardcoded types):
In plaats van een statische `spot_types` tabel, gebruiken we dynamische categorie filter specs.
*   **Restaurants:** Bijv. Guachinche, Bodega, Tapas, Asador, Fine dining.
*   **Bars:** Bijv. Beachbar, Cocktailbar, Pub, Bierbar, Loungebar.

## Meertaligheid (Multilingual System)

Alles in het systeem is vertaalbaar via JSON fields.

*   **Talen:** nl, en, es, de, fr.
*   **Prioriteit:** Gebruikersvoorkeur > Geselecteerde taal > Browser locale > Default (en) > Origineel.
*   **Implementatie:** Spatie Laravel Translatable wordt aanbevolen.
