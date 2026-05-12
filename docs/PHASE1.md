# BenLocal - Fase 1 Documentatie

Dit document beschrijft de opgeleverde resultaten van **Fase 1** van het BenLocal platform.

## Core Architectuur & Fundatie

De basis van het platform is gebouwd met een focus op schaalbaarheid en meertaligheid.

*   **Framework:** Laravel 12
*   **PHP Versie:** 8.3+
*   **Database:** PostgreSQL/MySQL ready (migraties geoptimaliseerd)
*   **Meertaligheid:** Gebruik van `spatie/laravel-translatable` met JSON velden in de database.

---

## Geïmplementeerde Componenten

### 1. Configuratie & Enums
*   **Config:** `config/benlocal.php` bevat alle basisinstellingen zoals beschikbare talen, standaardregio's, community types en status flows.
*   **Enums:** Geïmplementeerd in `app/Enums` voor consistente statusbeheer:
    *   `CampaignStatus`
    *   `ModerationStatus`
    *   `ReviewReactionType`
    *   `SpotLifecycleStatus`
    *   `UserRegionStatus`

### 2. Database Architectuur (Modellen & Migraties)
Alle tabellen zijn voorzien van de gevraagde velden, indexes, en waar zinvol `softDeletes`.

*   **Users & Auth:** Uitgebreid gebruikersprofiel inclusief regio-status en reputatie tracking.
*   **Geografie:** Hiërarchische structuur van `Communities`, `Regions`, `Areas` en `Places`.
*   **Taxonomie:** `Sectors` en `Categories` met een dynamisch systeem voor `Rating Specs` en `Filter Specs`.
*   **Spots:** De kern van het platform, inclusief meertalige velden, locatiegegevens en metadata.
*   **Interacties:**
    *   `Recommendations` & `Reviews` (met foto's en reacties).
    *   `SpotVisits` voor verificatie van bezoeken.
    *   `Follows` systeem.
*   **Campagnes:** Volledig systeem voor `Campaigns`, `Submissions` en `Claiming` van spots.
*   **Beheer & GDPR:**
    *   `ModerationActions` & `ContentReports`.
    *   `GDPR Exports` & `Deletions` logica.
    *   `CookieConsents` tracking.
*   **CMS & Media:** Basis voor `Pages` en een generiek `Media` model.

### 3. Eloquent Relaties & Casts
*   Alle modellen bevatten de noodzakelijke `belongsTo`, `hasMany` en `morphMany` relaties.
*   JSON velden zijn gecast naar arrays/collections voor gemakkelijke manipulatie.
*   Translatable velden zijn gedefinieerd in de modellen.

### 4. Seeders & Testdata
De database kan volledig gevuld worden met testdata via `php artisan db:seed --class=BenLocalSeeder`.

Inbegrepen testdata:
*   **Regio:** Tenerife (Costa Adeje, Puerto Colón).
*   **Sectoren:** Food & Drinks.
*   **Categorieën:** Restaurants, Bars.
*   **Specs:** Voorbeeld filter specs (bijv. Venue Type voor restaurants).
*   **Users:** Testaccounts voor Jan, Carlos en Sofie.
*   **Spots:** Voorbeeld restaurant in Puerto Colón.

---

## Hoe te gebruiken

1.  **Migraties uitvoeren:**
    ```bash
    php artisan migrate
    ```
2.  **Database seeden:**
    ```bash
    php artisan db:seed --class=BenLocalSeeder
    ```
3.  **Configuratie inzien:**
    Bekijk `config/benlocal.php` voor de platforminstellingen.

---

*Status Fase 1: Voltooid*
