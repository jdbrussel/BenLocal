# BenLocal - Fase 3 Documentatie: Auth & Localization

Dit document beschrijft de resultaten van **Fase 3**, gericht op authenticatie, lokalisatie, gebruikersvoorkeuren en de basis voor de gebruikerservaring.

## Authenticatie Stack

De authenticatie is API-first opgezet en ondersteunt zowel traditionele als sociale logins.

*   **Laravel Sanctum:** Voor veilige API-authenticatie.
*   **Laravel Socialite:** Geïntegreerd voor sociale logins.
    *   **Google Login:** Ondersteund.
    *   **Facebook Login:** Ondersteund.
*   **Socialite Functies:** Automatische accountcreatie, koppeling op basis van e-mail en synchronisatie van avatars.

---

## Lokalisatie Systeem

Een robuust systeem voor taalbeheer dat rekening houdt met zowel gasten als ingelogde gebruikers.

*   **Configuratie:** Beschikbare talen (`nl`, `en`, `es`, `de`, `fr`) gedefinieerd in `config/benlocal.php`.
*   **LocaleService:** Centraal beheer van taalresolutie en validatie.
*   **SetLocale Middleware:** Automatische detectie van taal op basis van:
    1.  Voorkeur van de ingelogde gebruiker.
    2.  Geselecteerde taal in cookies/localStorage.
    3.  Browserinstellingen (`Accept-Language`).
    4.  Standaardtaal (`en`).

---

## Gebruikersvoorkeuren & Theme

Gebruikers kunnen hun ervaring personaliseren via diverse instellingen.

*   **Theme System:** Ondersteuning voor `light`, `dark` en `system` modes.
*   **ThemePersistence:** Voorkeuren worden opgeslagen in de database (voor gebruikers) of lokale opslag (voor gasten).
*   **UserSettingsService:** Voor het beheren van taal-, thema- en privacy-instellingen.

---

## Cookie Consent

Een volledig AVG-compliant systeem voor toestemmingsbeheer.

*   **Categorieën:** Noodzakelijk, Analyse, Personalisatie en Marketing.
*   **CookieConsentService:** Beheert de status van toestemming voor zowel gasten als gebruikers.
*   **Integratie:** Middleware die cookies blokkeert zolang er geen toestemming is gegeven.

---

## Local Status & Onboarding

De basis voor het identificeren van lokale experts en het begeleiden van nieuwe gebruikers.

*   **UserRegionStatusService:** Beheert de status van een gebruiker in een regio (bijv. "Local", "Verified Local", "Regular Visitor").
*   **Confidence Scores:** Automatische berekening op basis van woonplaats en activiteit.
*   **Onboarding Foundation:** Tracking van de voortgang van de nieuwe gebruiker door de welkomststappen.

---

## Seeders & Testdata (Fase 3)

De `Phase3Seeder` is toegevoegd om realistische testscenario's te creëren.

*   **Testgebruikers:** Gebruikers voor alle ondersteunde talen en thema-instellingen.
*   **Social Login Scenario's:** Voorbeelddata voor Google en Facebook koppelingen.
*   **Local Status Voorbeelden:** Geverifieerde locals in Tenerife met verschillende scores.
*   **Interests & Preferences:** Realistische voorkeuren zoals "Hidden gem lover" en "Traditional Canarian food".

---

*Status Fase 3: Voltooid*
