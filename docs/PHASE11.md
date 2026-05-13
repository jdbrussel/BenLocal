# BenLocal - Documentatie Fase 11: Business Claim Flow & Owner Dashboard

Dit document beschrijft de implementatie van **Fase 11**. In deze fase is het Business Claim systeem en het Owner Dashboard toegevoegd, waardoor ondernemers hun eigen bedrijfspagina kunnen beheren.

## Doelstellingen

1.  **Business Claim Flow:** Ondernemers kunnen hun zaak claimen via een beveiligde token-link.
2.  **Owner Dashboard:** Een apart Filament panel voor eigenaren om hun gegevens te beheren.
3.  **Review Management:** Eigenaren kunnen reageren op reviews van klanten.
4.  **Verification Workflow:** Admin controle over claimverzoeken en verificatie van bedrijven.

---

## 1. Business Claim System

### Componenten
*   **`ClaimToken` Model:** Beheert unieke tokens die naar ondernemers worden gestuurd.
*   **`SpotClaim` Model:** Houdt claimverzoeken en hun status (pending, approved, rejected) bij.
*   **`ClaimService`:** Service voor het genereren en valideren van tokens.
*   **`BusinessClaimController`:** Handelt de publieke flow af voor het indienen van een claim.

### Flow
1.  Admin genereert een `ClaimToken` voor een spot.
2.  Ondernemer ontvangt link: `/claim/{token}`.
3.  Ondernemer vult het claimformulier in.
4.  Admin keurt de claim goed of wijst deze af in het Admin Panel.
5.  Bij goedkeuring krijgt de gebruiker de rol `OWNER` en toegang tot het `/owner` panel.

---

## 2. Owner Dashboard (Filament)

Toegankelijk via `/owner` voor gebruikers met de rol `owner`.

### Functionaliteiten voor Eigenaren
*   **My Spots:** Beheer contactgegevens, openingstijden en foto's van de geclaimde zaak.
*   **Review Responses:** Bekijk reviews en plaats officiële reacties.
*   **Suggest Community:** Stel relevante community-profielen voor voor de eigen zaak.
*   **Report Error:** Meld onjuiste informatie aan het support team.

### Beperkingen
*   Eigenaren kunnen de naam van de zaak niet zelf wijzigen (vereist admin).
*   Eigenaren kunnen de ranking of badges niet beïnvloeden.
*   Eigenaren kunnen reviews niet zelf verwijderen.

---

## 3. Admin & Support Panel

*   **Spot Claims:** Beheer de wachtrij van claimverzoeken.
*   **Claim Tokens:** Overzicht en beheer van uitgegeven claim tokens.
*   **Approval Actions:** Directe acties om claims goed te keuren, af te wijzen of extra informatie op te vragen.

---

## 4. Email Notificaties

De volgende e-mails zijn geïmplementeerd:
*   `RestaurantRecommended`: "Uw restaurant is aanbevolen" (bevat claim link).
*   `ClaimApproved`: "Claim goedgekeurd".
*   `ClaimRejected`: "Claim afgewezen".
*   `MoreInfoNeeded`: "Extra informatie nodig voor uw claim".

---

## 5. Seeders

*   **`BusinessClaimSeeder`:** Genereert testdata voor alle statussen van claims (pending, approved, rejected) en tokens.

---

## 6. Test Scenario's

*   **Token Validatie:** Controleer of tokens verlopen of slechts eenmaal gebruikt kunnen worden.
*   **Owner Access:** Verifieer dat alleen geautoriseerde eigenaren toegang hebben tot hun eigen spots.
*   **Field Permissions:** Test dat eigenaren alleen toegestane velden kunnen bewerken.
*   **Approval Flow:** Verifieer dat bij goedkeuring de juiste rollen en permissies worden toegekend.
