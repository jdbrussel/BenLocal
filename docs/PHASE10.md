# BenLocal - Documentatie Fase 10: AI Translation & AI Spot Enrichment

Dit document beschrijft de implementatie van **Fase 10**. In deze fase is AI-ondersteuning toegevoegd voor vertalingen en het verrijken van spotgegevens.

## Doelstellingen

1.  **AI Translation:** Automatisch vertalen van ontbrekende velden in modellen (Spot, etc.) naar ondersteunde talen (NL, EN, ES, DE, FR).
2.  **AI Spot Enrichment:** Automatisch aanvullen van ontbrekende spot-informatie op basis van AI-analyse van beschikbare data.
3.  **Admin Controle:** AI-verrijking vereist altijd goedkeuring van een administrator voordat de data definitief wordt toegepast.

---

## 1. AI Infrastructuur

### Componenten
*   **`AIProviderInterface`:** Contract voor AI-providers (vertaling en verrijking).
*   **`OpenAIProvider`:** Implementatie (placeholder) voor OpenAI-diensten.
*   **`DeepLProvider`:** Implementatie (placeholder) voor DeepL-vertalingen.
*   **`AITranslationService`:** Beheert de vertaallogica en integratie met modellen.
*   **`AIEnrichmentService`:** Beheert de verrijkingslogica voor spots en campagne-inzendingen.

---

## 2. Translation System

### Functionaliteit
*   **Vertaal Ontbrekende Velden:** Vertaalt JSON-velden die nog geen waarde hebben voor een specifieke taal.
*   **Forceer Vertaling:** Optie om bestaande vertalingen te overschrijven (standaard uitgeschakeld).
*   **Taalbehoud:** Slaat `original_language` en `translated_at` op voor tracking. `translated_at` wordt bij elke nieuwe vertaling bijgewerkt.

### Commands & Jobs
*   `php artisan benlocal:translate-missing {--force} {--model=all}`: Bulk vertaling van ontbrekende velden. Ondersteunt `Spot`, `Review` en `Recommendation`.
*   `TranslateModelFieldJob`: Asynchrone verwerking van vertalingen.

---

## 3. Enrichment System

### Functionaliteit
*   **Data Verrijking:** AI probeert velden zoals adres, telefoon, website, openingstijden en coördinaten aan te vullen.
*   **Confidence Scores:** AI geeft een betrouwbaarheidsscore mee aan de verrijkte data.
*   **Pending State:** Verrijkte data wordt opgeslagen in `ai_enrichment_data` en moet door een admin worden goedgekeurd.

### Commands & Jobs
*   `php artisan benlocal:enrich-pending-spots`: Bulk verrijking van nog niet geverifieerde spots.
*   `EnrichSpotJob`: Verrijkt een specifieke spot.
*   `EnrichCampaignSpotJob`: Verrijkt een campagne-inzending om matching te verbeteren.

---

## 4. Filament Admin Integratie

*   **Spot Resource:**
    *   `Translate Missing` actie: Start vertaling voor de huidige spot.
    *   `AI Enrich` actie: Start AI-verrijking voor de spot.
    *   `Approve AI` actie: Past de AI-verrijkte data toe op de spot (zichtbaar wanneer data beschikbaar is).
*   **Campaign Submissions:**
    *   `AI Enrich` actie: Helpt bij het identificeren van ingezonden spots door AI-analyse.

---

## 5. Test Scenario's

*   **Translation Overwrite:** Verifieer dat `force=false` bestaande vertalingen niet overschrijft.
*   **Enrichment Lifecycle:** Test het proces van verrijking (pending) naar goedkeuring (applied).
*   **Command Execution:** Controleer of de Artisan commands de juiste jobs dispatchen.
