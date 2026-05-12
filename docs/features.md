# Core Features & Mechanismen

BenLocal onderscheidt zich door specifieke mechanismen die vertrouwen en lokale expertise centraal stellen.

## Aanbevelingen vs. Reviews

Dit is het kernonderscheid van het platform:

### 1. Aanbevelingen (Recommendations)
*   **Alleen voor Locals:** Alleen gebruikers met de status 'Local' in een specifieke regio kunnen een spot actief aanbevelen.
*   **Betekenis:** "Ik sta achter deze plek als local."
*   **Impact:** Heeft het hoogste gewicht in rankings en de ontdekking van 'Hidden Gems'.

### 2. Reviews
*   **Voor iedereen:** Bezoekers, toeristen en locals kunnen reviews schrijven.
*   **Doel:** Het bevestigen, deels bevestigen of tegenspreken van bestaande aanbevelingen.
*   **Validatie:** Andere gebruikers kunnen reviews valideren (mee eens / deels mee eens / oneens).

## AI Translation System

Om een wereldwijd publiek te bedienen zonder handmatige vertalingslast:

*   **Services:** `AITranslationService`, `TranslateModelFieldJob`.
*   **Trait:** `HasAiTranslations` voor modellen.
*   **Data:** Slaat `original_language` en `translated_at` op.
*   **Toepassing:** Spots, aanbevelingen, reviews, pagina's en notificaties.

## Gebruikersreputatie (User Reputation)

Reputatie is niet generiek, maar contextueel:

*   **Niveaus:** Per regio, per categorie, per sector en per community.
*   **Titels:** Trusted Local, Curator, Food Explorer, Hidden Gem Hunter.
*   **Factoren:** Succesvolle aanbevelingen, validatie van reviews, volgers en activiteit.

## Hidden Gems

Een 'Hidden Gem' is een plek die:
1.  Weinig totale aanbevelingen heeft (geen massatoerisme).
2.  Sterke lokale steun heeft (hoog percentage aanbevelingen door locals).
3.  Hoog gevalideerd is door anderen.
4.  Dynamisch van status kan veranderen naarmate de populariteit toeneemt.

## Campagne Systeem

Generieke flows voor snelle user engagement:
*   **Flow:** Landingpage -> Spot matching -> AI Enrichment (indien onbekend) -> Aanbeveling -> Account creatie.
*   **Voorbeeld:** "Tafelen in Tenerife".
