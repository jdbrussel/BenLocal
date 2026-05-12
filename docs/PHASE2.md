# BenLocal - Fase 2 Documentatie: Admin Panel

Dit document beschrijft de resultaten van **Fase 2**, gericht op het beheerpaneel (Filament Admin).

## Filament Admin Integratie

Voor het beheer van de data is Filament v4 geïntegreerd met Laravel 12.

*   **Panel URL:** `/admin`
*   **Resources Pad:** `app/Filament/Admin/Resources`
*   **Support Pad:** `app/Filament/Support`

---

## Geïmplementeerde Resources

De volgende resources zijn beschikbaar in het admin paneel voor volledig CRUD-beheer:

### 1. Locations (Geografie)
Beheer van de hiërarchische locatiestructuur.
*   **Regions:** Provincies of eilanden (bijv. Tenerife).
*   **Areas:** Grotere gebieden binnen een regio (bijv. Costa Adeje).
*   **Places:** Specifieke plaatsen of dorpen (bijv. Puerto Colón).

### 2. Taxonomy (Categorisering)
Beheer van de sector- en categoriestructuur.
*   **Sectors:** Hoofdgroepen (bijv. Food & Drinks).
*   **Categories:** Specifieke types (bijv. Restaurants).

### 3. Spots (Locaties)
Het kernbeheer van alle "Spots" op het platform.
*   Inclusief tab-gebaseerde formulieren voor overzichtelijkheid.
*   Beheer van status, contactgegevens, locatie (lat/long) en AI-verrijkte data.

---

## Custom Componenten

### TranslatableField
Om het beheer van meertalige velden (JSON) te vereenvoudigen, is er een `TranslatableField` component ontwikkeld.

*   **Locatie:** `app/Filament/Support/TranslatableField.php`
*   **Functionaliteit:** Genereert automatisch een Tab-interface voor alle geconfigureerde talen in `config/benlocal.available_languages`.
*   **Ondersteunde types:** `text`, `textarea`, `rich` (RichEditor).

---

## Technische Notities (Fase 2)

*   **Namespace Correcties:** Tijdens de implementatie is de overstap gemaakt naar de nieuwe Filament `Schemas` namespace voor layout-componenten (`Tabs`, `Tab`).
*   **Meertaligheid:** De resources maken gebruik van de `benlocal.default_language` configuratie voor het weergeven van labels in tabellen en select-lijsten.
*   **Validatie:** Unieke slugs worden gecontroleerd met uitzondering van het huidige record bij bewerken.

---

*Status Fase 2: Voltooid*
