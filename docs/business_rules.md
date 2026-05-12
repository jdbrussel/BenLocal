# Business Rules & Scoring

Dit document bevat de logica achter de scores en de regels voor gebruikersinteractie.

## Scoring Services

Het platform gebruikt verschillende services om de rangschikking en betrouwbaarheid te berekenen:

1.  **RecommendationScoreService:** Berekent de waarde van een lokale aanbeveling op basis van de reputatie van de aanbeveler.
2.  **ReviewWeightService:** Bepaalt hoe zwaar een review weegt (geverifieerd bezoek vs. niet-geverifieerd).
3.  **UserReputationService:** Verwerkt acties naar reputatiepunten per regio/categorie.
4.  **SpotRankingService:** De uiteindelijke ranking van een spot voor een specifieke gebruiker.
5.  **HiddenGemService:** Identificeert spots die voldoen aan de 'local-only' populariteitscriteria.

## User Region Status

Een gebruiker kan per regio een verschillende status hebben:
*   **Tourist:** Eerste bezoek of weinig activiteit.
*   **Visitor:** Regelmatig bezoeker.
*   **Local:** Woont in de regio of heeft sterke banden (IP support, adres, community activiteit).
*   **Verified Local:** Door moderators of via verificatieproces bevestigde local.

## Trust & Safety Regels

*   **Claiming:** Bedrijfseigenaren kunnen spots claimen, maar dit mag de ranking nooit direct positief beïnvloeden.
*   **Moderatie:** Moderators kunnen dubbele spots samenvoegen, reviews verbergen en spam detecteren.
*   **Verified Visits:** GPS check-ins of QR-codes bij een locatie verhogen de betrouwbaarheid van een review aanzienlijk.

## Ranking Factoren

De volgorde waarin spots aan een gebruiker worden getoond, hangt af van:
1.  **Local Trust:** Hoeveel locals bevelen dit aan?
2.  **Community Match:** Past deze plek bij de communities die de gebruiker heeft ingeschakeld?
3.  **Personal Trust:** Wordt dit aanbevolen door mensen die de gebruiker volgt?
4.  **Recency:** Hoe recent zijn de aanbevelingen en reviews?
5.  **Distance:** Nabijheid tot de huidige locatie van de gebruiker.
